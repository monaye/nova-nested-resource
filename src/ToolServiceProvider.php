<?php

namespace Monaye\NovaNestedResourceTools;

use Laravel\Nova\Nova;
use Illuminate\Support\Collection;
use Laravel\Nova\Events\ServingNova;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\LengthAwarePaginator;
use Monaye\NovaNestedResourceTools\Http\Middleware\Authorize;

class ToolServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'nova-nested-resource-tools');

        $this->app->booted(function () {
            $this->routes();

            Collection::macro('flattenTree', function ($childrenField = 'children', $levelAttribute = 'level')
            {
                $toProcess = $this->items;
                $processed = [];
                while($item = array_shift($toProcess))
                {
                    $item->$levelAttribute ++;
                    $processed[] = $item;
                    if (count($item->$childrenField) > 0) {
                        $children = array_reverse($item->$childrenField->items);
                        foreach ($children as $child) {
                            $child->$levelAttribute = $item->$levelAttribute;
                            array_unshift($toProcess,$child);
                        }
                    }
                }
                return Collection::make($processed);
            });
    
             /**
             * Paginate a standard Laravel Collection.
             *
             * @param int $perPage
             * @param int $total
             * @param int $page
             * @param string $pageName
             * @return array
             */
            Collection::macro('paginate', function($perPage, $total = null, $page = null, $pageName = 'page') {
                $page = $page ?: LengthAwarePaginator::resolveCurrentPage($pageName);
    
                return new LengthAwarePaginator(
                    $this->forPage($page, $perPage),
                    $total ?: $this->count(),
                    $perPage,
                    $page,
                    [
                        'path' => LengthAwarePaginator::resolveCurrentPath(),
                        'pageName' => $pageName,
                    ]
                );
            });
        });

        Nova::serving(function (ServingNova $event) {
            //
        });
    }

    /**
     * Register the tool's routes.
     *
     * @return void
     */
    protected function routes()
    {
        if ($this->app->routesAreCached()) {
            return;
        }

        Route::middleware(['nova', Authorize::class])
                ->prefix('/categories')
                ->group(__DIR__.'/../routes/api.php');
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
