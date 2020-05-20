<?php

namespace Ngodink\Blog;

use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Eloquent\Factory;

use Ngodink\Blog\Models\BlogPost;
use Ngodink\Blog\Observers\BlogPostObserver;

class BlogServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot()
    {
        // Load configuration
        $this->mergeConfigFrom(__DIR__.'/../config/blog.php', 'blog');

        // Load migrations
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');

        // Load observers
        $this->registerObservers();
    }

    /**
     * Register the observers.
     */
    public function registerObservers()
    {
        BlogPost::observe(BlogPostObserver::class);
    }
}
