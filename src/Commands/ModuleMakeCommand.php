<?php
/**
 * Mcow Laravel modules.
 *
 * @author  Muzaffardjan Karaev
 * @link    https://karaev.uz
 * Created: 12.01.2022 / 12:53
 */
declare(strict_types=1);

namespace Mcow\LaravelModules\Commands;

use Illuminate\Console\Command;
use Mcow\LaravelModules\Commands\Generators\ModuleGenerator;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

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

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments(): array
    {
        return [
            ['name', InputArgument::IS_ARRAY, 'The names of modules will be created.']
        ];
    }

    /**
     * @return array
     */
    protected function getOptions(): array
    {
        return [
            ['force', 'f', InputOption::VALUE_NONE, 'Force the operation to run when the module already exists.']
        ];
    }

    public function handle()
    {
        $names = $this->argument('name');

        foreach ($names as $name) {
            ModuleGenerator::make($name)
                ->setModuleRepository($this->laravel['modules'])
                ->setForce($this->option('force'))
                ->setConfigRepository($this->laravel['config'])
                ->setFileSystem($this->laravel['files'])
                ->generate();
        }

        echo 'Finish!!!' . PHP_EOL;
    }
}
