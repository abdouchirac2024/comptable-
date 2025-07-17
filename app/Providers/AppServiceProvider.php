<?php

namespace App\Providers;

use App\Models\Categorie;
use App\Repositories\CategorieRepository;
use App\Repositories\Interfaces\CategorieRepositoryInterface;
use App\Repositories\ContactRepository;
use App\Repositories\Interfaces\ContactRepositoryInterface;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Binding du repository pour l'injection de dépendances
        $this->app->bind(CategorieRepositoryInterface::class, function ($app) {
            return new CategorieRepository(new Categorie());
        });
        $this->app->bind(ContactRepositoryInterface::class, function ($app) {
            return new ContactRepository(new \App\Models\Contact());
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
