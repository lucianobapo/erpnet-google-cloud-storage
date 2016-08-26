<?php

/**
 * Created by PhpStorm.
 * User: luciano
 * Date: 24/08/16
 * Time: 02:23
 */
namespace ErpNET\GoogleCloudStorage\Providers;

use Google_Auth_AssertionCredentials;
use Google_Client;
use Google_Service_Storage;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\ServiceProvider;
use League\Flysystem\Filesystem;
use Superbalist\Flysystem\GoogleStorage\GoogleStorageAdapter;

class ErpnetGoogleCloudStorageProvider extends ServiceProvider
{

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        // TODO: Implement register() method.
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
//        $this->loadViewsFrom(__DIR__.'/../../resources/views', 'erpnetSocialAuth');

        $this->publishes([
//            __DIR__.'/../../config/erpnetSocialAuth.php' => config_path('erpnetSocialAuth.php'),
//            __DIR__.'/../../resources/views' => base_path('resources/views/vendor/erpnetSocialAuth'),
//            __DIR__.'/Migrations' => base_path('database/migrations'),
        ]);

//        $this->app->config->set('auth.model', $this->app->config->get('easyAuthenticator.model'));

//        include __DIR__.'/routes.php';

//        $this->app->register(\Collective\Html\HtmlServiceProvider::class);
//        $this->app->register(\Laravel\Socialite\SocialiteServiceProvider::class);

        Storage::extend('gcs', function ($app, $config) {
            $credentials = new Google_Auth_AssertionCredentials(
                $config['service_account'],
                [
                    Google_Service_Storage::DEVSTORAGE_FULL_CONTROL
                ],
                file_get_contents($config['service_account_certificate']),
                $config['service_account_certificate_password']
            );

            $client = new Google_Client();
            $client->setAssertionCredentials($credentials);

            $service = new Google_Service_Storage($client);
            $adapter = new GoogleStorageAdapter($service, $config['bucket']);

            return new Filesystem($adapter);
        });
    }
}