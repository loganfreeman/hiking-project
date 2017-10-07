<?php

/*
 * This file is part of Laravel Contact.
 *
 * (c) Graham Campbell <graham@alt-three.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Providers;

use App\Http\Controllers\ContactController;
use Illuminate\Routing\Router;
use Illuminate\Support\ServiceProvider;
use App\Mailer;

/**
 * This is the contact service provider class.
 *
 * @author Graham Campbell <graham@alt-three.com>
 */
class ContactServiceProvider extends ServiceProvider
{
    /**
     * Boot the service provider.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadViewsFrom(realpath(__DIR__.'/../../resources/views/contact'), 'contact');

    }


    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->registerContactMailer();
        $this->registerContactController();
    }

    /**
     * Register the contact mailer class.
     *
     * @return void
     */
    protected function registerContactMailer()
    {
        $this->app->bind('contact.mailer', function ($app) {
            $mail = $app['mailer'];
            $home = $app['url']->to('/');
            $path = $app['config']['contact.path'];
            $email = $app['config']['contact.email'];
            $name = $app['config']['app.name'];

            return new Mailer($mail, $home, $path, $email, $name);
        });

        $this->app->alias('contact.mailer', Mailer::class);
    }

    /**
     * Register the contact controller class.
     *
     * @return void
     */
    protected function registerContactController()
    {
        $this->app->bind(ContactController::class, function ($app) {
            $throttler = $app['throttle']->get($app['request'], 2, 30);
            $path = $app['config']['contact.path'];

            return new ContactController($throttler, $path);
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return string[]
     */
    public function provides()
    {
        return [
            'contact.mailer',
        ];
    }
}
