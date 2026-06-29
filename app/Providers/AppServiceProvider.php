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
use Innovity\ApprovalEngine\Contracts\ApproverResolverInterface;
use Innovity\ApprovalEngine\Events\ApprovalCompleted;
use Innovity\ApprovalEngine\Events\ApprovalRejected;
use Illuminate\Support\Facades\Event;
use App\Listeners\WorkflowStatusListener;

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
        
        Event::listen(ApprovalCompleted::class, [WorkflowStatusListener::class, 'handleCompleted']);
        Event::listen(ApprovalRejected::class, [WorkflowStatusListener::class, 'handleRejected']);

        //Google Api Key Configuration
        // Avoid error during migrate
        if (!Schema::hasTable('api_keys')) {
            return;
        }

        // Cache for performance
        $mapsKey = cache()->rememberForever('google_maps_api_key', function () {
            return ApiKey::first()?->google_maps_api_key;
        });

        // Override config if DB value exists
        if (!empty($mapsKey)) {
            config()->set('services.google.maps_key', $mapsKey);
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
    }
}
