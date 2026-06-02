<?php

namespace App\Console\Commands;

use App\Models\Employee\Employee;
use App\Models\Setting\Notification;
use App\Models\Setting\NotificationSetting;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
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
    protected $description = 'Check for employee birthdays and document expiries based on notification settings';

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

        // 1. Birthdays
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
            $this->sendToNonEmployees('Birthday Alert', $message, ['employee_id' => $employee->id, 'type' => 'birthday']);
        }
    }

    protected function checkExpiry(string $column, int $days, Carbon $today, string $label)
    {
        $targetDate = $today->copy()->addDays($days)->toDateString();

        $employees = Employee::where($column, $targetDate)->get();

        foreach ($employees as $employee) {
            $message = "{$label} Expiry: {$employee->full_name}'s {$label} will expire on {$targetDate}.";
            
            // Send to the employee if they have a user account
            $user = User::where('employee_id', $employee->id)->first();
            if ($user) {
                $this->createNotification($user, "{$label} Expiry Alert", $message, ['employee_id' => $employee->id, 'type' => strtolower($label)]);
            }

            // Send to non-employees
            $this->sendToNonEmployees("{$label} Expiry Alert", $message, ['employee_id' => $employee->id, 'type' => strtolower($label)]);
        }
    }

    protected function checkProbation(int $days, Carbon $today)
    {
        $targetDate = $today->copy()->addDays($days)->toDateString();

        // Probation end is calculated as date_of_join + probation_duration (if any)
        // Or directly from confirmation_date if that represents the end
        // Requirement says "Probation Period end", I'll use office_info.
        
        $employees = Employee::whereHas('officeInfo', function($query) use ($targetDate) {
            // Logic: date_of_join + probation_duration = targetDate
            // In SQL: DATE_ADD(date_of_join, INTERVAL probation_duration DAY) = targetDate
            $query->whereRaw("DATE_ADD(date_of_join, INTERVAL probation_duration DAY) = ?", [$targetDate]);
        })->get();

        foreach ($employees as $employee) {
            $message = "Probation Period End: {$employee->full_name}'s probation period will end on {$targetDate}.";
            
            // Send to the employee
            $user = User::where('employee_id', $employee->id)->first();
            if ($user) {
                $this->createNotification($user, "Probation End Alert", $message, ['employee_id' => $employee->id, 'type' => 'probation']);
            }

            // Send to non-employees
            $this->sendToNonEmployees("Probation End Alert", $message, ['employee_id' => $employee->id, 'type' => 'probation']);
        }
    }

    protected function sendToNonEmployees(string $title, string $message, array $data)
    {
        $users = User::where('user_type', '!=', 'Employee')->get();
        foreach ($users as $user) {
            $this->createNotification($user, $title, $message, $data);
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
