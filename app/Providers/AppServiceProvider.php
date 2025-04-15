<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\SystemSetting;
use Illuminate\Support\Facades\View;
use Carbon\Carbon;
use Firebase\JWT\JWT;
use SocialiteProviders\Manager\SocialiteWasCalled;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    // public function boot(): void
    // {
    //     View::composer('*', function ($view) {
    //         $systemSetting = SystemSetting::first();
    //         $view->with('systemSetting', $systemSetting);
    //     });

    //     $clientId   = config('services.apple.client_id');
    //     $teamId     = config('services.apple.team_id');
    //     $keyId      = config('services.apple.key_id');
    //     $privateKeyPath = config('services.apple.private_key');

    //     if (!file_exists($privateKeyPath)) {
    //         throw new \Exception("Apple private key file not found at: $privateKeyPath");
    //     }

    //     $privateKey = trim(file_get_contents($privateKeyPath));

    //     $now = Carbon::now()->timestamp;
    //     $exp = Carbon::now()->addMonths(6)->timestamp;

    //     $payload = [
    //         'iss' => $teamId, // Apple Team ID
    //         'iat' => $now, // Issued at
    //         'exp' => $exp, // Expiration time
    //         'aud' => 'com.theholistichorseworks.app',
    //         'sub' => $clientId, // Apple Client ID
    //     ];

    //     // Generate JWT
    //     try {
    //         $clientSecret = JWT::encode($payload, $privateKey, 'ES256', $keyId);
    //     } catch (\Exception $e) {
    //         throw new \Exception("Failed to generate Apple client secret: " . $e->getMessage());
    //     }

    //     // Inject into config
    //     config()->set('services.apple.client_secret', $clientSecret);

    //     // Register Apple provider
    //     $this->app['events']->listen(SocialiteWasCalled::class, function ($event) {
    //         $event->extendSocialite('apple', \SocialiteProviders\Apple\Provider::class);
    //     });
    // }
}
