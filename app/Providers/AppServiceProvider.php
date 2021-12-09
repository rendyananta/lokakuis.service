<?php

namespace App\Providers;

use Illuminate\Contracts\Filesystem\Factory;
use Illuminate\Contracts\Routing\ResponseFactory;
use App\Http\Responses\AttachmentResponse;
use Illuminate\Foundation\Application;
use Illuminate\Http\Request;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(AttachmentResponse::class, function (Application $application) {
            return new AttachmentResponse(
                $application->make(Request::class),
                $application->make(Factory::class),
                $application->make(ResponseFactory::class)
            );
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
