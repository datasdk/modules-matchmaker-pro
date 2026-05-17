<?php

namespace Modules\Tasks\Providers;

use Illuminate\Support\ServiceProvider;
use Modules\Tasks\Providers\BarryvdhServiceProvider;
use Illuminate\Routing\Router;
use Modules\Tasks\Models\Tasks;
use Modules\Media\Services\MediaLibraryService;
use Turahe\Counters\Models\Counter;
use Modules\Tasks\Observers\TasksObserver;
use DataSDK\Categories\Http\Controllers\Api\CategoriesController;
use DataSDK\Categories\Models\Categories as BaseCategories;
use Modules\Tasks\Models\Categories;
use Modules\Tasks\Observers\CategoriesObserver;
use Illuminate\Support\Facades\Schema;
use Modules\Tasks\Models\Matches;
use Modules\Tasks\Observers\MatchObserver;
use Illuminate\Contracts\Http\Kernel;
use Modules\Tasks\Http\Middleware\AddStatusGroupsHeader;
use Modules\Chat\Models\Conversation;
use Modules\Chat\Http\Controllers\Api\ConversationController;
use Modules\Tasks\Observers\TaskRatingsObserver;
use Modules\Tasks\Models\TaskRatings;
use Modules\Reviews\Models\Interaction;
use Modules\Tasks\Models\Companies;
use Multicaret\Acquaintances\AcquaintancesServiceProvider;
use Modules\Cron\Services\TotemCommandService;



class TasksServiceProvider extends ServiceProvider
{
    /**
     * @var string $moduleName
     */
    protected $moduleName = 'Tasks';

    /**
     * @var string $moduleNameLower
     */
    protected $moduleNameLower = 'tasks';


    /**
     * Boot the application events.
     *
     * @return void
     */
    public function boot()
    {

        $this->registerTranslations();
        $this->registerConfig();
        $this->registerViews();
        $this->loadMigrationsFrom(module_path($this->moduleName, 'Database/Migrations'));
        $this->observers();
        $this->registerRouteMiddleware();
        $this->registerVendor();

        $this->whitelist();

   


    }


     public function registerVendor(){

     //   $this->app->register(AcquaintancesServiceProvider::class);

     }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {

        $this->app->register(RouteServiceProvider::class);

        $this->app->register(EventServiceProvider::class);

        $this->app->register(CommandServiceProvider::class);
        
    
        $this->registerCounter();

       
        Tasks::registerCronJob(['tasks:update-status']);

       

    }

 
    protected function observers(){

        Tasks::observe(TasksObserver::class);

        Matches::observe(MatchObserver::class);

        TaskRatings::observe(TaskRatingsObserver::class);

    }

        
    protected function whitelist(){

        Interaction::whitelist(Tasks::class);

        Interaction::whitelist(Companies::class);

    }


    protected function registerCounter(){

     
        if(!Schema::hasTable('counters')){ 
            
            return false; 
        
        }

        Counter::firstOrCreate([
            'key' => 'views',
            'name' => 'tasks',
        ],[
            'initial_value' => 1,
            'step' => 1 
        ]);


        Counter::firstOrCreate([
            'key' => 'usage',
            'name' => 'tasks',
        ],[
            
            'initial_value' => 0,
            'step' => 1 
        ]);


    }


    /**
     * Register config.
     *
     * @return void
     */
    protected function registerConfig()
    {
        // Registerer og merger Tasks' hovedkonfiguration
        $this->mergeConfigFrom(
            module_path($this->moduleName, 'Config/config.php'),
            'tasks'
        );

        // Registerer og merger acquaintances konfiguration
        $this->mergeConfigFrom(
            module_path($this->moduleName, 'Config/acquaintances.php'),
            'acquaintances'
        );

      

        // Publicerer konfigurationerne (valgfrit, hvis du ønsker at kunne overskrive dem)
        $this->publishes([
            module_path($this->moduleName, 'Config/config.php') => config_path('tasks.php'),
            module_path($this->moduleName, 'Config/acquaintances.php') => config_path('acquaintances.php')
        ], 'config');

        // Test om konfigurationerne bliver læst korrekt
        // Du kan slette denne linje, når du har bekræftet at det virker:


        config()->set('counter.models.table_pivot_name', 'counterables');
      
        
             
     
      
    }


    private function registerRouteMiddleware(): void
    {
        
        $middleware = AddStatusGroupsHeader::class;

        /** @var Router $router */
        $router = $this->app->make(Router::class);
        $router->aliasMiddleware('addStatusGroupsHeader', $middleware);
    }

    /**
     * Register views.
     *
     * @return void
     */
    protected function registerViews()
    {
        $viewPath = resource_path('views/modules/' . $this->moduleNameLower);
        $sourcePath = module_path($this->moduleName, 'Resources/views');

        $this->publishes([
            $sourcePath => $viewPath
        ], ['views', $this->moduleNameLower . '-module-views']);

        $this->loadViewsFrom(array_merge($this->getPublishableViewPaths(), [$sourcePath]), $this->moduleNameLower);
    }

    /**
     * Register translations.
     *
     * @return void
     */
    protected function registerTranslations()
    {
        $langPath = resource_path('lang/modules/' . $this->moduleNameLower);

        if (is_dir($langPath)) {
            $this->loadTranslationsFrom($langPath, $this->moduleNameLower);
            $this->loadJsonTranslationsFrom($langPath, $this->moduleNameLower);
        } else {
            $this->loadTranslationsFrom(module_path($this->moduleName, 'Resources/lang'), $this->moduleNameLower);
            $this->loadJsonTranslationsFrom(module_path($this->moduleName, 'Resources/lang'), $this->moduleNameLower);
        }
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [];
    }

    /**
     * Get publishable view paths.
     *
     * @return array
     */
    private function getPublishableViewPaths(): array
    {
        $paths = [];
        foreach (\Config::get('view.paths') as $path) {
            if (is_dir($path . '/modules/' . $this->moduleNameLower)) {
                $paths[] = $path . '/modules/' . $this->moduleNameLower;
            }
        }
        return $paths;
    }
}
