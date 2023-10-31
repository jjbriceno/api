<?php

namespace App\Providers;

use App\Interfaces\MusicSheetRepositoryInterface;
use App\Repositories\MusicSheetRepository;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(MusicSheetRepositoryInterface::class, MusicSheetRepository::class);
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
