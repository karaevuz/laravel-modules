<?php
/**
 * Mcow Laravel modules.
 *
 * @author  Muzaffardjan Karaev
 * @link    https://karaev.uz
 * Created: 12.01.2022 / 12:58
 */
declare(strict_types=1);

namespace Mcow\LaravelModules\Commands\Generators;

use Illuminate\Support\Str;
use Mcow\LaravelModules\Extensions\McowTemplate;
use Mcow\LaravelModules\ModuleRepository;
use Illuminate\Console\Command as Console;
use Illuminate\Config\Repository as ConfigRepository;
use Illuminate\Filesystem\Filesystem;

/**
 * Class ModuleGenerator
 * @package Mcow\LaravelModules\Commands\Generators
 */
class ModuleGenerator
{
    /** @var string */
    protected string $moduleName;

    /** @var ModuleRepository */
    protected $moduleRepository;

    /** @var bool */
    protected bool $force = false;

    /** @var Console */
    protected $consoleCommand;

    /** @var ConfigRepository */
    protected $configRepository;

    /** @var Filesystem */
    protected $fileSystem;

    /**
     * ModuleGenerator constructor.
     * @param string $moduleName
     */
    public function __construct(string $moduleName)
    {
        $this->moduleName = $moduleName;
    }

    /**
     * @return ModuleRepository
     */
    public function getModuleRepository(): ModuleRepository
    {
        return $this->moduleRepository;
    }

    /**
     * @param ModuleRepository $moduleRepository
     * @return $this
     */
    public function setModuleRepository(ModuleRepository $moduleRepository): ModuleGenerator
    {
        $this->moduleRepository = $moduleRepository;

        return $this;
    }

    /**
     * @return bool
     */
    public function getForce(): bool
    {
        return $this->force;
    }

    /**
     * @param bool $force
     * @return ModuleGenerator
     */
    public function setForce(bool $force): ModuleGenerator
    {
        $this->force = $force;

        return $this;
    }

    /**
     * @return Console
     */
    public function getConsoleCommand(): Console
    {
        return $this->consoleCommand;
    }

    /**
     * @param Console $consoleCommand
     * @return ModuleGenerator
     */
    public function setConsoleCommand(Console $consoleCommand): ModuleGenerator
    {
        $this->consoleCommand = $consoleCommand;

        return $this;
    }

    /**
     * @param ConfigRepository $configRepository
     * @return $this
     */
    public function setConfigRepository(ConfigRepository $configRepository): ModuleGenerator
    {
        $this->configRepository = $configRepository;

        return $this;
    }

    /**
     * @return ConfigRepository
     */
    public function getConfigRepository(): ConfigRepository
    {
        return $this->configRepository;
    }

    /**
     * @param Filesystem $filesystem
     * @return $this
     */
    public function setFileSystem(Filesystem $filesystem): ModuleGenerator
    {
        $this->fileSystem = $filesystem;

        return $this;
    }

    /**
     * @return Filesystem
     */
    public function getFileSystem(): Filesystem
    {
        return $this->fileSystem;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return Str::studly($this->moduleName);
    }

    /**
     * @return array
     */
    public function getFolders(): array
    {
        return $this->getConfigRepository()->get('modules.paths.folders', []);
    }

    /**
     * @return void
     */
    public function generateFolders()
    {
        foreach ($this->getFolders() as $folder) {
            $path = $this->getModuleRepository()->getModulePath($this->getName()) . '/' . $folder;

            if (!$this->getFileSystem()->isDirectory($path)) {
                $this->getFileSystem()->makeDirectory($path, 0755, true);
                $this->getFileSystem()->put($path . '/.gitkeep', '');
            }
        }
    }

    public function getReplacement(string $template)
    {

    }

    public function getTemplateContents(string $template)
    {
        return McowTemplate::make($template, $this->getReplacement($template))->render();
    }

    public function generateModuleJsonFile()
    {
        $path = $this->getModuleRepository()->getModulePath($this->getName()) . 'module.json';

        if (!$this->getFileSystem()->isDirectory($dir = dirname($path))) {
            $this->getFileSystem()->makeDirectory($dir, 0775, true);
        }

        $this->getFileSystem()->put($path, $this->getTemplateContents('json'));
        $this->getConsoleCommand()->info("Created : {$path}");
    }

    public function generate()
    {
        $name = $this->getName();

        if ($this->getModuleRepository()->has($name)) {
            if ($this->getForce()) {
                $this->getModuleRepository()->delete($name);
            } else {
                $this->getConsoleCommand()->error("Module [{$name}] already exist!");

                return E_ERROR;
            }
        }

        $this->generateFolders();
        $this->generateModuleJsonFile();
    }

    /**
     * @param string $moduleName
     * @return ModuleGenerator
     */
    public static function make(string $moduleName): ModuleGenerator
    {
        return new static($moduleName);
    }
}
