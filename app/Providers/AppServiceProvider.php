<?php

namespace App\Providers;

use App\Models\Setting\ApiKey;
use App\Models\Setting\MailSetting;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;

use Illuminate\Support\Facades\Gate;

use Illuminate\Support\Facades\View;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Route;
use App\Services\ApproverResolver;
use Illuminate\Support\Facades\Event;
use Innovity\ApprovalEngine\Contracts\ApproverResolverInterface;
use Innovity\ApprovalEngine\Events\ApprovalCompleted;
use Innovity\ApprovalEngine\Events\ApprovalRejected;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Map of modules to their dedicated listener classes.
     */
    private array $workflowListeners = [
        'promotion'                => \App\Listeners\Workflow\PromotionWorkflowListener::class,
        'increment'                => \App\Listeners\Workflow\IncrementWorkflowListener::class,
        'leave'                    => \App\Listeners\Workflow\LeaveWorkflowListener::class,
        'salary'                   => \App\Listeners\Workflow\PayrollWorkflowListener::class,
        'bonus'                    => \App\Listeners\Workflow\PayrollWorkflowListener::class,
        'profile-update'           => \App\Listeners\Workflow\ProfileUpdateWorkflowListener::class,
        'office-information'       => \App\Listeners\Workflow\ProfileUpdateWorkflowListener::class,
        'employee-policy'          => \App\Listeners\Workflow\ProfileUpdateWorkflowListener::class,
        'salary-breakdown'         => \App\Listeners\Workflow\ProfileUpdateWorkflowListener::class,
        'employee-bank-account'    => \App\Listeners\Workflow\ProfileUpdateWorkflowListener::class,
    ];
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(ApproverResolverInterface::class, ApproverResolver::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Implicitly grant "Super Admin" role all permissions
        // This works in the app by using gate-related functions like auth()->user->can() and @can()
        Gate::before(function ($user, $ability) {
            return $user->hasRole('Super Admin') ? true : null;
        });

        // Provide roles to the Edit Login Info modal
        View::composer('employee.partials.modal.edit_login_modal', function ($view) {
            $view->with('roles', Role::all());
        });

        if (env('APP_FORCE_HTTPS', false)) {
            URL::forceScheme('https');
        }
        
        Paginator::useBootstrap();

        // Custom Blade directives for dynamic profile field configuration
        \Illuminate\Support\Facades\Blade::directive('required_asterisk', function ($expression) {
            return "<?php if(\\App\\HelperClass::isProfileFieldRequired({$expression})): echo '<span class=\"text-danger\">*</span>'; endif; ?>";
        });

        \Illuminate\Support\Facades\Blade::directive('required_attribute', function ($expression) {
            return "<?php if(\\App\\HelperClass::isProfileFieldRequired({$expression})): echo 'required'; endif; ?>";
        });
        
        // 1. Listen for Approval Completed Events dynamically
        Event::listen(ApprovalCompleted::class, function (ApprovalCompleted $event) {
            $module = $event->approvalRequest->workflow->module;
            
            if (isset($this->workflowListeners[$module])) {
                $listener = app($this->workflowListeners[$module]);
                if (method_exists($listener, 'handleCompleted')) {
                    $listener->handleCompleted($event);
                }
            }
        });

        // 2. Listen for Approval Rejected Events dynamically
        Event::listen(ApprovalRejected::class, function (ApprovalRejected $event) {
            $module = $event->approvalRequest->workflow->module;
            
            if (isset($this->workflowListeners[$module])) {
                $listener = app($this->workflowListeners[$module]);
                if (method_exists($listener, 'handleRejected')) {
                    $listener->handleRejected($event);
                }
            }
        });

        //Google Api Key Configuration
        // Avoid error during migrate
        if (Schema::hasTable('api_keys')) {
            // Cache for performance
            $mapsKey = cache()->rememberForever('google_maps_api_key', function () {
                return ApiKey::first()?->google_maps_api_key;
            });

            // Override config if DB value exists
            if (!empty($mapsKey)) {
                config()->set('services.google.maps_key', $mapsKey);
            }
        }

        // 1. Prevent errors during migrations or if table doesn't exist yet
        if (Schema::hasTable('mail_settings')) {

            $mail = MailSetting::first();
            if ($mail) {
                // 2. Map Database columns to Laravel Config keys
                $data = [
                    'mail.mailers.smtp.host'       => $mail->mail_host,
                    'mail.mailers.smtp.port'       => $mail->port,
                    'mail.mailers.smtp.encryption' => $mail->encryption_type,
                    'mail.mailers.smtp.username'   => $mail->sender_email,
                    'mail.mailers.smtp.password'   => $mail->password,
                    'mail.from.address'            => $mail->sender_email,
                    'mail.from.name'               => $mail->app_name,
                ];

                // 3. Apply the changes globally for this request
                Config::set($data);
            }
        }

        // Notify approvers when a new step request is created
        \Innovity\ApprovalEngine\Models\ApprovalStepRequest::created(function ($stepRequest) {
            if ($stepRequest->status->value === 'pending') {
                $resolver = app(\Innovity\ApprovalEngine\Contracts\ApproverResolverInterface::class);
                $approvable = $stepRequest->approvalRequest->approvable;
                
                if ($approvable) {
                    $approverIds = $resolver->resolve((string) $stepRequest->workflowStep->id, $approvable);
                    if (!empty($approverIds)) {
                        $users = \App\Models\User::whereIn('id', $approverIds)->get();
                        foreach ($users as $user) {
                            try {
                                $user->notify(new \App\Notifications\Approval\ApprovalActionRequiredNotification($stepRequest));
                            } catch (\Exception $e) {
                                \Illuminate\Support\Facades\Log::error('Mail Notification error: ' . $e->getMessage());
                            }
                            
                             try {
                                 $moduleName = $stepRequest->approvalRequest->workflow->module ?? '';
                                 $module = ucfirst($moduleName ?: 'Item');
                                 
                                 if ($approvable instanceof \App\Models\Employee\ProfileUpdateRequest) {
                                     $url = route('profile_update_requests.show', $approvable->id, false);
                                 } else {
                                     $url = \Illuminate\Support\Facades\Route::has($moduleName . '.show') 
                                             ? route($moduleName . '.show', $approvable->id, false) 
                                             : '/' . $moduleName;
                                 }

                                 app(\App\Services\Setting\NotificationServices::class)->createNotification(
                                     $user->user_type->value ?? $user->user_type,
                                     $user->id,
                                     'Approval Action Required',
                                     "You have a new $module approval request pending your action.",
                                     ['url' => $url, 'type' => 'approval_request']
                                 );
                             } catch (\Exception $e) {
                                \Illuminate\Support\Facades\Log::error('Custom Notification error: ' . $e->getMessage());
                            }
                        }
                    }
                }
            }
        });
    }
}
