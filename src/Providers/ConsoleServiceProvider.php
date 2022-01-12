<?php
/**
 * Mcow Laravel modules.
 *
 * @author  Muzaffardjan Karaev
 * @link    https://karaev.uz
 * Created: 12.01.2022 / 12:48
 */
declare(strict_types=1);

namespace Mcow\LaravelModules\Providers;

use Illuminate\Support\ServiceProvider;

/**
 * Class ConsoleServiceProvider
 * @package Mcow\LaravelModules\Providers
 */
class ConsoleServiceProvider extends ServiceProvider
{
    protected array $commands = [

    ];

    /**
     * Register all commands
     *
     * @return void
     */
    public function register()
    {
        $this->commands(array_merge($this->commands, config('modules.commands')));
    }

    /**
     * @return array
     */
    public function provides(): array
    {
        return $this->commands;
    }
}
