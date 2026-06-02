<?php

namespace App\Console\Commands;

use App\Models\Employee\Employee;
use App\Models\Setting\Notification;
use App\Models\Setting\NotificationSetting;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class CheckAlerts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:check-alerts';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check for employee birthdays and document expiries based on notification settings (Range Logic)';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $settings = NotificationSetting::first();
        if (!$settings) {
            $this->warn('Notification settings not found. Please configure them first.');
            return;
        }

        $today = Carbon::today();
        $this->info("Checking alerts for: " . $today->toDateString());

        // 1. Birthdays (Still exact day only)
        if ($settings->birthday_days > 0) {
            $this->checkBirthdays($settings->birthday_days, $today);
        }

        // 2. Visa Expiry
        if ($settings->visa_days > 0) {
            $this->checkExpiry('visa_expiry', $settings->visa_days, $today, 'Visa');
        }

        // 3. Work Permit Expiry
        if ($settings->work_permit_days > 0) {
            $this->checkExpiry('work_expiry', $settings->work_permit_days, $today, 'Work Permit');
        }

        // 4. Passport Expiry
        if ($settings->passport_days > 0) {
            $this->checkExpiry('passport_expiry', $settings->passport_days, $today, 'Passport');
        }

        // 5. License Expiry
        if ($settings->license_days > 0) {
            $this->checkExpiry('license_expiry', $settings->license_days, $today, 'License');
        }

        // 6. Probation End
        if ($settings->probation_days > 0) {
            $this->checkProbation($settings->probation_days, $today);
        }

        $this->info('Alert check completed.');
    }

    protected function checkBirthdays(int $days, Carbon $today)
    {
        $targetDate = $today->copy()->addDays($days);
        $month = $targetDate->month;
        $day = $targetDate->day;

        $employees = Employee::whereMonth('date_of_birth', $month)
            ->whereDay('date_of_birth', $day)
            ->get();

        foreach ($employees as $employee) {
            $message = "Upcoming Birthday: {$employee->full_name} on {$targetDate->format('M d')}.";
            
            // For birthdays, we only alert once on the exact day relative to threshold
            $this->sendToNonEmployees('Birthday Alert', $message, [
                'employee_id' => $employee->id, 
                'type' => 'birthday',
                'target_date' => $targetDate->toDateString()
            ]);
        }
    }

    protected function checkExpiry(string $column, int $days, Carbon $today, string $label)
    {
        // RANGE LOGIC: Anyone whose expiry is between TODAY and threshold
        $thresholdDate = $today->copy()->addDays($days)->toDateString();

        $employees = Employee::whereBetween($column, [$today->toDateString(), $thresholdDate])->get();

        foreach ($employees as $employee) {
            $expiryDate = $employee->{$column};
            $message = "{$label} Expiry: {$employee->full_name}'s {$label} will expire on {$expiryDate}.";
            
            // PREVENT DUPLICATES: Only notify if we haven't already notified for THIS specific expiry date
            $data = [
                'employee_id' => $employee->id, 
                'type' => strtolower($label),
                'expiry_date' => $expiryDate
            ];

            // Send to the employee
            $user = User::where('employee_id', $employee->id)->first();
            if ($user && !$this->notificationExists($user->id, $data)) {
                $this->createNotification($user, "{$label} Expiry Alert", $message, $data);
            }

            // Send to non-employees
            $nonEmployeeUsers = User::where('user_type', '!=', 'Employee')->get();
            foreach ($nonEmployeeUsers as $neUser) {
                if (!$this->notificationExists($neUser->id, $data)) {
                    $this->createNotification($neUser, "{$label} Expiry Alert", $message, $data);
                }
            }
        }
    }

    protected function checkProbation(int $days, Carbon $today)
    {
        $thresholdDate = $today->copy()->addDays($days)->toDateString();

        $employees = Employee::whereHas('officeInfo', function($query) use ($today, $thresholdDate) {
            $query->whereRaw("DATE_ADD(date_of_join, INTERVAL probation_duration DAY) BETWEEN ? AND ?", [
                $today->toDateString(), 
                $thresholdDate
            ]);
        })->with('officeInfo')->get();

        foreach ($employees as $employee) {
            $probationEndDate = Carbon::parse($employee->officeInfo->date_of_join)
                ->addDays($employee->officeInfo->probation_duration)
                ->toDateString();

            $message = "Probation Period End: {$employee->full_name}'s probation period will end on {$probationEndDate}.";
            
            $data = [
                'employee_id' => $employee->id, 
                'type' => 'probation',
                'end_date' => $probationEndDate
            ];

            // Send to the employee
            $user = User::where('employee_id', $employee->id)->first();
            if ($user && !$this->notificationExists($user->id, $data)) {
                $this->createNotification($user, "Probation End Alert", $message, $data);
            }

            // Send to non-employees
            $nonEmployeeUsers = User::where('user_type', '!=', 'Employee')->get();
            foreach ($nonEmployeeUsers as $neUser) {
                if (!$this->notificationExists($neUser->id, $data)) {
                    $this->createNotification($neUser, "Probation End Alert", $message, $data);
                }
            }
        }
    }

    /**
     * Check if a notification with the same data already exists for a user
     */
    protected function notificationExists(int $userId, array $data): bool
    {
        return Notification::where('user_id', $userId)
            ->whereJsonContains('data', $data)
            ->exists();
    }

    protected function sendToNonEmployees(string $title, string $message, array $data)
    {
        $users = User::where('user_type', '!=', 'Employee')->get();
        foreach ($users as $user) {
            if (!$this->notificationExists($user->id, $data)) {
                $this->createNotification($user, $title, $message, $data);
            }
        }
    }

    protected function createNotification(User $user, string $title, string $message, array $data)
    {
        Notification::create([
            'user_type' => $user->user_type,
            'user_id' => $user->id,
            'title' => $title,
            'message' => $message,
            'data' => $data,
        ]);
    }
}
