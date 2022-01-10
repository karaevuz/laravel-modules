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
     * Booting the package.
     */
    public function boot()
    {
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
    }

    /**
     * {@inheritdoc}
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
}
