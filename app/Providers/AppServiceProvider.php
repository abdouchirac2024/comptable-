<?php

namespace App\Providers;

use App\Models\Categorie;
use App\Repositories\CategorieRepository;
use App\Repositories\Interfaces\CategorieRepositoryInterface;
use App\Repositories\ContactRepository;
use App\Repositories\Interfaces\ContactRepositoryInterface;
use Illuminate\Support\ServiceProvider;
use App\Repositories\ServiceRepository;
use App\Repositories\Interfaces\ServiceRepositoryInterface;
use App\Repositories\MissionRepository;
use App\Repositories\Interfaces\MissionRepositoryInterface;
use App\Repositories\PartenaireRepository;
use App\Repositories\Interfaces\PartenaireRepositoryInterface;
use App\Repositories\FormationRepository;
use App\Repositories\Interfaces\FormationRepositoryInterface;
use App\Repositories\ArticleBlogRepository;
use App\Repositories\Interfaces\ArticleBlogRepositoryInterface;

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
        $this->app->bind(ServiceRepositoryInterface::class, ServiceRepository::class);
        $this->app->bind(MissionRepositoryInterface::class, MissionRepository::class);
        $this->app->bind(PartenaireRepositoryInterface::class, PartenaireRepository::class);
        $this->app->bind(FormationRepositoryInterface::class, FormationRepository::class);
        $this->app->bind(ArticleBlogRepositoryInterface::class, ArticleBlogRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
