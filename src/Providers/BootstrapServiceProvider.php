<?php
/**
 * Mcow Laravel modules.
 *
 * @author  Muzaffardjan Karaev
 * @link    https://karaev.uz
 * Created: 10.01.2022 / 15:04
 */
declare(strict_types=1);

namespace Mcow\LaravelModules\Providers;

use Dotenv\Repository\RepositoryInterface;
use Illuminate\Support\ServiceProvider;

/**
 * Class BootstrapServiceProvider
 * @package Mcow\LaravelModules\Providers
 */
class BootstrapServiceProvider extends ServiceProvider
{
    /**
     * Booting the package.
     */
    public function boot(): void
    {
        $this->app[RepositoryInterface::class]->boot();
    }

    /**
     * Register the provider.
     */
    public function register(): void
    {
        $this->app[RepositoryInterface::class]->register();
    }
}
