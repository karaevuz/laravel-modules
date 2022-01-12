<?php
/**
 * Mcow Laravel modules.
 *
 * @author  Muzaffardjan Karaev
 * @link    https://karaev.uz
 * Created: 12.01.2022 / 12:33
 */
declare(strict_types=1);

namespace Mcow\LaravelModules;

use Illuminate\Cache\CacheManager;
use Illuminate\Container\Container;
use Illuminate\Contracts\Config\Repository as ConfigRepository;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Str;
use Mcow\LaravelModules\Contracts\ModuleInterface;
use Mcow\LaravelModules\Extensions\McowCollection;
use Mcow\LaravelModules\Extensions\McowJson;
use PHPUnit\Exception;

/**
 * Class ModuleRepository
 * @package Mcow\LaravelModules
 */
class ModuleRepository implements ModuleInterface
{
    /** @var Application */
    protected $app;

    /** @var ConfigRepository */
    protected $configRepository;

    /** @var Filesystem */
    protected $fileSystem;

    /** @var CacheManager */
    protected $cacheManager;

    /** @var string */
    protected string $path;

    /**
     * ModuleRepository constructor.
     * @param Container $app
     */
    public function __construct(Container $app)
    {
        $this->app = $app;

        $this->configRepository = $app['config'];
        $this->fileSystem       = $app['files'];
        $this->cacheManager     = $app['cache'];

        $this->path = $app['config']->get('modules.path.modules');
    }

    /**
     * @param ...$args
     * @return Module
     */
    public function createModule(...$args): Module
    {
        return new Module(...$args);
    }

    /**
     * @return ConfigRepository
     */
    public function getConfigRepository(): ConfigRepository
    {
        return $this->configRepository;
    }

    /**
     * @return Filesystem
     */
    public function getFileSystem(): Filesystem
    {
        return $this->fileSystem;
    }

    /**
     * @return CacheManager
     */
    public function getCacheManager(): CacheManager
    {
        return $this->cacheManager;
    }

    /**
     * @return string
     */
    public function getPath(): string
    {
        return $this->path ?: $this->getConfigRepository()->get(
            'modules.paths.modules',
            base_path('modules')
        );
    }

    /**
     * @return array
     */
    public function scan(): array
    {
        $modules = [];
        $path = Str::endsWith($this->getPath(), '/*')
            ? $this->getPath()
            : Str::finish($this->getPath(), '/*');

        $manifests = $this->getFileSystem()->glob("{$path}/module.json");
        is_array($manifests) || $manifests = [];

        foreach ($manifests as $manifest) {
            $name = McowJson::make($manifest)->get($manifest);

            $modules[$name] = $this->createModule($this->app, $name, dirname($manifest));
        }

        return $modules;
    }

    /**
     * @return array
     */
    public function getFromCache(): array
    {
        $key = $this->getConfigRepository()->get('modules.cache.key');
        $lifetime = $this->getConfigRepository()->get('modules.cache.lifetime');

        return $this->getCacheManager()->remember($key, $lifetime, function () {
            return (new McowCollection())->toArray();
        });
    }

    /**
     * @param array $cached
     * @return array
     */
    public function reformatCache(array $cached): array
    {
        $modules = [];

        foreach ($cached as $name => $module) {
            $modules[$name] = $this->createModule($this->app, $name, $module['path']);
        }

        return $modules;
    }

    /**
     * Get all modules.
     *
     * @return array
     */
    public function all(): array
    {
        if ($this->getConfigRepository()->get('modules.cache.enabled')) {
            return $this->reformatCache($this->getFromCache());
        }

        return $this->scan();
    }

    /**
     * @param string $moduleName
     * @return bool
     */
    public function has(string $moduleName): bool
    {
        return array_key_exists($moduleName, $this->all());
    }

    /**
     * @param string $moduleName
     * @return Module|null
     */
    public function find(string $moduleName): ?Module
    {
        /** @var Module $module */
        foreach ($this->all() as $module) {
            if (strtolower($moduleName) === $module->getLowerName()) {
                return $module;
            }
        }

        return null;
    }

    /**
     * @param string $moduleName
     * @return Module
     */
    public function findOrFail(string $moduleName): Module
    {
        /** @var Module|null $module */
        $module = $this->find($moduleName);

        if (is_null($module)) {
            throw new \RuntimeException("Module [{$moduleName}] does not exist!");
        }

        return $module;
    }

    /**
     * @param string $modulePath
     * @return string
     */
    public function getModulePath(string $modulePath): string
    {
        try {
            return $this->findOrFail($modulePath)->getPath() . '/';
        } catch (Exception $exception) {
            return $this->getPath() . '/' . Str::studly($modulePath) . '/';
        }
    }

    /**
     * @param string $moduleName
     * @return bool
     */
    public function delete(string $moduleName): bool
    {
        return $this->findOrFail($moduleName)->delete();
    }
}
