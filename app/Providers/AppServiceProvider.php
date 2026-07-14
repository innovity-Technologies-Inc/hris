<?php

namespace App\Providers;

use Illuminate\Pagination\Paginator;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\View;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Event;
use App\Services\ApproverResolver;
use Innovity\ApprovalEngine\Contracts\ApproverResolverInterface;
use Innovity\ApprovalEngine\Events\ApprovalCompleted;
use Innovity\ApprovalEngine\Events\ApprovalRejected;

class AppServiceProvider extends ServiceProvider
{
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
        Relation::morphMap([
            'transfer' => \App\Models\Transfer\Transfer::class,
            'increment' => \App\Models\Payroll\Increment::class,
            'decrement' => \App\Models\Payroll\Decrement::class,
            'promotion' => \App\Models\Payroll\Promotion::class,
            'demotion' => \App\Models\Payroll\Demotion::class,
        ]);

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
        
        // 1. Load Database Configurations (Google Maps & SMTP Mail Settings)
        app(\App\Services\Setting\SystemConfigLoaderService::class)->loadConfigs();

        // 2. Listen for Approval completed and rejected events dynamically
        Event::listen(ApprovalCompleted::class, [app(\App\Services\Setting\WorkflowEventDispatcherService::class), 'handleCompleted']);
        Event::listen(ApprovalRejected::class, [app(\App\Services\Setting\WorkflowEventDispatcherService::class), 'handleRejected']);

        // 3. Notify approvers and run auto-approval when a new step request is created
        \Innovity\ApprovalEngine\Models\ApprovalStepRequest::created(function ($stepRequest) {
            app(\App\Services\Setting\WorkflowStepRequestService::class)->handleCreated($stepRequest);
        });

        // 4. Automatically sanitize/cast Workflow role and user IDs to integers (enables Spatie hasRole check to work properly with IDs)
        \Innovity\ApprovalEngine\Models\Workflow::retrieved(function ($workflow) {
            if (is_array($workflow->exclude_role_ids)) {
                $workflow->exclude_role_ids = array_map('intval', $workflow->exclude_role_ids);
            }
            if (is_array($workflow->includer_role_ids)) {
                $workflow->includer_role_ids = array_map('intval', $workflow->includer_role_ids);
            }
            if (is_array($workflow->exclude_user_ids)) {
                $workflow->exclude_user_ids = array_map('intval', $workflow->exclude_user_ids);
            }
            if (is_array($workflow->includer_user_ids)) {
                $workflow->includer_user_ids = array_map('intval', $workflow->includer_user_ids);
            }
        });

        \Innovity\ApprovalEngine\Models\Workflow::saving(function ($workflow) {
            if (is_array($workflow->exclude_role_ids)) {
                $workflow->exclude_role_ids = array_map('intval', $workflow->exclude_role_ids);
            }
            if (is_array($workflow->includer_role_ids)) {
                $workflow->includer_role_ids = array_map('intval', $workflow->includer_role_ids);
            }
            if (is_array($workflow->exclude_user_ids)) {
                $workflow->exclude_user_ids = array_map('intval', $workflow->exclude_user_ids);
            }
            if (is_array($workflow->includer_user_ids)) {
                $workflow->includer_user_ids = array_map('intval', $workflow->includer_user_ids);
            }
        });
    }
}
