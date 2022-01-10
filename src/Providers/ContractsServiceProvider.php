<?php
/**
 * Mcow Laravel modules.
 *
 * @author  Muzaffardjan Karaev
 * @link    https://karaev.uz
 * Created: 10.01.2022 / 14:56
 */
declare(strict_types=1);

namespace Mcow\LaravelModules\Providers;

use Dotenv\Repository\RepositoryInterface;
use Illuminate\Support\ServiceProvider;
use Mcow\LaravelModules\ModuleRepository;

/**
 * Class ContractsServiceProvider
 * @package Mcow\LaravelModules\Providers
 */
class ContractsServiceProvider extends ServiceProvider
{
    /**
     * Register some binding.
     */
    public function register()
    {
        $this->app->bind(RepositoryInterface::class, ModuleRepository::class);
    }
}
