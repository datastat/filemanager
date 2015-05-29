<?php namespace datastat\FileManager;

use Illuminate\Support\ServiceProvider;

class FileManagerServiceProvider extends ServiceProvider {

    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * Bootstrap the application events.
     * php artisan vendor:publish --provider="datastat\FileManager\FileManagerServiceProvider"
     * @return void
     */
    public function boot()
    {
        $this->loadViewsFrom(__DIR__.'/Resources/Views', 'filemanager');
        include(__DIR__.'/Http/routes.php');
        
        // Publish your migrations
        $this->publishes([
                __DIR__.'/Database/Migrations/' =>
                    database_path('/migrations')
            ], 'migrations');

        $this->publishes([
            __DIR__.'/../../../assets/filemanager' =>
                public_path('vendor/filemanager'),
        ], 'public');

    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        // $this->app['filemanager'] = $this->app->share(function($app)
        // {
        //     return new Datatables($app['request']);
        // });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return array();
    }

}
