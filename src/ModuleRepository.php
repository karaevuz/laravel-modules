<?php
/**
 * Mcow Laravel modules.
 *
 * @author  Muzaffardjan Karaev
 * @link    https://karaev.uz
 * Created: 09.01.2022 / 00:51
 */
declare(strict_types=1);

namespace Mcow\LaravelModules;

use Illuminate\Support\Str;
use Mcow\LaravelModules\Contracts\ModuleInterface;
use Illuminate\Container\Container;
use Illuminate\Config\Repository as ConfigRepository;
use Illuminate\Filesystem\Filesystem;
use Mcow\LaravelModules\Extensions\McowJson;
use Illuminate\Cache\CacheManager;
use Illuminate\Support\Collection;

/**
 * Class ModuleRepository
 * @package Mcow\LaravelModules
 */
class ModuleRepository implements ModuleInterface
{
    /** @var \Illuminate\Contracts\Foundation\Application */
    protected $app;

    /** @var ConfigRepository */
    protected $configRepository;

    /** @var string */
    protected string $path;

    /** @var Filesystem */
    private $fileSystem;

    /** @var CacheManager */
    private $cacheManager;

    /**
     * ModuleRepository constructor.
     * @param Container $app
     */
    public function __construct(Container $app)
    {
        $this->app = $app;
        $this->configRepository = $app['config'];

        $this->path = $this->configRepository->get('modules.paths.modules');
        $this->fileSystem = $app['files'];
        $this->cacheManager = $app['cache'];
    }

    /**
     * @param ...$args
     * @return Module
     */
    protected function createModule(...$args): Module
    {
        return new Module(...$args);
    }

    /**
     * Get scanned modules path.
     *
     * @return string
     */
    public function getScanPaths(): string
    {
        return Str::endsWith($this->path, '/*') ? $this->path : Str::finish($this->path, '/*');
    }

    /**
     * Get & scan all modules.
     *
     * @return array
     */
    public function getOrScan(): array
    {
        $modules = [];
        $manifests = $this->fileSystem->glob("{$this->getScanPaths()}/module.json");

        is_array($manifests) || $manifests = [];

        foreach ($manifests as $manifest) {
            $name = McowJson::make($manifest)->get('name');

            $modules[$name] = $this->createModule($this->app, $name, dirname($manifest));
        }

        return $modules;
    }

    /**
     * Get cached modules.
     *
     * @return array
     */
    public function getCached(): array
    {
        $key = $this->configRepository->get('modules.cache.key');
        $lifetime = $this->configRepository->get('modules.cache.lifetime');

        return $this->cacheManager->remember($key, $lifetime, function () {
            return (new Collection($this->getOrScan()))->toArray();
        });
    }

    /**
     * Format the cached data as array of modules.
     *
     * @param array $cached
     * @return array
     */
    protected function formatCached(array $cached): array
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
        if (!$this->configRepository->get('modules.cache.enabled')) {
            return $this->getOrScan();
        }

        return $this->formatCached($this->getCached());
    }

    /**
     * Determine whether the given module exist.
     *
     * @param string $moduleName
     * @return bool
     */
    public function has(string $moduleName): bool
    {
        return array_key_exists($moduleName, $this->all());
    }
}
