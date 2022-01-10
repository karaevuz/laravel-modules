<?php
/**
 * Mcow Laravel modules.
 *
 * @author  Muzaffardjan Karaev
 * @link    https://karaev.uz
 * Created: 09.01.2022 / 20:49
 */
declare(strict_types=1);

namespace Mcow\LaravelModules\Commands;

use Illuminate\Console\Command;
use Mcow\LaravelModules\Generators\ModuleGenerator;

/**
 * Class ModuleMakeCommand
 * @package Mcow\LaravelModules\Commands
 */
class ModuleMakeCommand extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'mcow:make-module';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new module.';

    public function handle()
    {
        $moduleNames = $this->argument('name');

        foreach ($moduleNames as $moduleName) {
            with(new ModuleGenerator($this->laravel, $moduleName))->generate();
        }
    }
}
