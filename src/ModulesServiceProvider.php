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
use Mcow\LaravelModules\ModuleRepository;
use Illuminate\Config\Repository as ConfigRepository;
use Mcow\LaravelModules\Providers\BootstrapServiceProvider;
use Mcow\LaravelModules\Providers\ConsoleServiceProvider;
use Mcow\LaravelModules\Providers\ContractsServiceProvider;

/**
 * Class ModulesServiceProvider
 * @package Mcow\LaravelModules
 */
class ModulesServiceProvider extends ServiceProvider
{
    /**
     * Register package's namespaces.
     *
     * @return void
     */
    protected function registerNamespaces()
    {
        $configPath = __DIR__ . '/../config/config.php';

        $this->mergeConfigFrom($configPath, 'modules');
        $this->publishes([$configPath => config('modules.php')], 'config');
    }

    /**
     * @return void
     */
    protected function registerModules()
    {
        $this->app->register(BootstrapServiceProvider::class);
    }

    /**
     * Booting the package.
     */
    public function boot()
    {
        $this->registerNamespaces();
        $this->registerModules();
    }

    /**
     * Register providers.
     */
    protected function registerProviders()
    {
        $this->app->register(ConsoleServiceProvider::class);
        $this->app->register(ContractsServiceProvider::class);
    }

    public function SetupCommandPath()
    {
        /** @var ConfigRepository $configRepository */
        $configRepository = $this->app['config'];

        // TODO Need optimize
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
     * Register all modules.
     */
    public function register()
    {
        $this->registerServices();
        $this->SetupCommandPath();
        $this->registerProviders();

        $this->mergeConfigFrom(__DIR__ . '/../config/config.php', 'modules');
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides(): array
    {
        return [ModuleInterface::class, 'modules'];
    }
}
