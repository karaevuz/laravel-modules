<?php
/**
 * Mcow Laravel modules.
 *
 * @author  Muzaffardjan Karaev
 * @link    https://karaev.uz
 * Created: 09.01.2022 / 08:40
 */
declare(strict_types=1);

namespace Mcow\LaravelModules\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;

/**
 * Class ConsoleServiceProvider
 * @package Mcow\LaravelModules\Providers
 */
class ConsoleServiceProvider extends ServiceProvider
{
    protected $commands = [];

    /**
     * @return array
     */
    private function resolveCommands(): array
    {
        $commands = [];

        foreach (config('modules.commands', $this->commands) as $command) {
            $commands[] = [];
        }

        return $commands;
    }

    public function register(): void
    {
        $this->commands($this->resolveCommands());
    }

    /**
     * @return array
     */
    public function provides(): array
    {
        return $this->commands;
    }
}
