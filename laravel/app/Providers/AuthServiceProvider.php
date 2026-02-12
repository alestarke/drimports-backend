<?php

namespace App\Providers;

use App\Models\Brand; 
use App\Policies\BrandPolicy; 
use App\Models\Product; 
use App\Policies\ProductPolicy;
use App\Models\User; 
use App\Policies\UserPolicy; 
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        User::class => UserPolicy::class,
        Product::class => ProductPolicy::class,
        Brand::class => BrandPolicy::class,
        Category::class => CategoryPolicy::class
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        //
    }
}