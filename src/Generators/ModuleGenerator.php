<?php
/**
 * Mcow Laravel modules.
 *
 * @author  Muzaffardjan Karaev
 * @link    https://karaev.uz
 * Created: 09.01.2022 / 21:06
 */
declare(strict_types=1);

namespace Mcow\LaravelModules\Generators;

use Illuminate\Container\Container;
use Mcow\LaravelModules\ModuleRepository;
use Illuminate\Console\Command as Console;
use Illuminate\Contracts\Config\Repository as ConfigRepository;

/**
 * Class ModuleGenerator
 * @package Mcow\LaravelModules\Generators
 */
class ModuleGenerator
{
    /** @var string */
    protected string $moduleName;

    /** @var ModuleRepository */
    protected $moduleRepository;

    /** @var Console */
    protected $console;

    /** @var ConfigRepository */
    protected $configRepository;

    /**
     * Force status.
     *
     * @var bool
     */
    protected bool $force = false;

    public function __construct(Container $app, string $moduleName)
    {
        $this->moduleName = $moduleName;
        $this->moduleRepository = $app['modules'];
        $this->configRepository = $app['config'];
    }

    /**
     * Set the laravel console instance.
     *
     * @param Console $console
     *
     * @return $this
     */
    public function setConsole(Console $console): ModuleGenerator
    {
        $this->console = $console;

        return $this;
    }

    /**
     * Set force status.
     *
     * @param bool|int $force
     *
     * @return $this
     */
    public function setForce($force): ModuleGenerator
    {
        $this->force = $force;

        return $this;
    }

    /**
     * Get the list of folders will created.
     *
     * @return array
     */
    public function getFolders(): array
    {
        return $this->configRepository->get('modules.paths.generator');
    }

    public function generateFolders()
    {
        foreach ($this->getFolders() as $key => $folder) {
            $folder = GenerateConfigReader::read($key);

            if ($folder->generate() === false) {
                continue;
            }

            $path = $this->module->getModulePath($this->getName()) . '/' . $folder->getPath();

            $this->filesystem->makeDirectory($path, 0755, true);
            if (config('modules.stubs.gitkeep')) {
                $this->generateGitKeep($path);
            }
        }
    }

    public function generate()
    {
        $name = $this->moduleName;

        if ($this->moduleRepository->has($name)) {
            if ($this->force) {
                $this->moduleRepository->delete($name);
            } else {
                $this->console->error("Module [{$name}] already exist!");

                return E_ERROR;
            }
        }

        $this->generateFolders();
    }
}
