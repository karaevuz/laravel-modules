<?php
/**
 * Mcow Laravel modules.
 *
 * @author  Muzaffardjan Karaev
 * @link    https://karaev.uz
 * Created: 09.01.2022 / 00:42
 */
declare(strict_types=1);

namespace Mcow\LaravelModules;

use Illuminate\Support\ServiceProvider;
use Mcow\LaravelModules\Contracts\ModuleInterface;
use Illuminate\Contracts\Config\Repository as ConfigRepository;
use Mcow\LaravelModules\Extensions\McowTemplate;

/**
 * Class ModulesServiceProvider
 *
 * @package Mcow\LaravelModules
 */
class ModulesServiceProvider extends ServiceProvider
{
    /**
     * @return void
     */
    protected function publishConfig()
    {
        $config = __DIR__ . '/../config/config.php';

        $this->mergeConfigFrom($config, 'modules');
        $this->publishes([$config => config_path('modules.php')], 'config');
    }

    public function boot()
    {
        $this->publishConfig();
    }

    /**
     * @return void
     */
    protected function registerServices()
    {
        $this->app->singleton(ModuleInterface::class, function ($app) {
            return new ModuleRepository($app);
        });

        $this->app->alias(ModuleInterface::class, 'modules');
    }

    /**
     * @return void
     */
    protected function registerTemplatePath()
    {
        /** @var ConfigRepository $configRepository */
        $configRepository = $this->app['config'];

        McowTemplate::setBasePath(
            $configRepository->get('modules.template.path') ?? __DIR__ . '/Commands/template'
        );
    }

    protected function registerProviders()
    {

    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->registerServices();
        $this->registerTemplatePath();
        $this->registerProviders();
    }
}
