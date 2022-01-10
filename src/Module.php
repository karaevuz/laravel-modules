<?php
/**
 * Mcow Laravel modules.
 *
 * @author  Muzaffardjan Karaev
 * @link    https://karaev.uz
 * Created: 09.01.2022 / 08:05
 */
declare(strict_types=1);

namespace Mcow\LaravelModules;

use Illuminate\Container\Container;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Foundation\AliasLoader;
use Illuminate\Foundation\ProviderRepository;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\Support\Traits\Macroable;
use Mcow\LaravelModules\Extensions\McowJson;
use Illuminate\Translation\Translator;

/**
 * Class Module
 *
 * @package Mcow\LaravelModules
 */
class Module
{
    use Macroable;

    /** @var \Illuminate\Contracts\Foundation\Application */
    protected $app;

    /** @var string */
    protected string $name;

    /** @var string */
    protected string $path;

    /** @var array */
    protected array $configJson = [];

    /** @var Filesystem */
    private $fileSystem;

    /** @var Translator */
    private $translator;

    /**
     * Module constructor.
     *
     * @param Container $app
     * @param string    $name
     * @param string    $path
     */
    public function __construct(Container $app, string $name, string $path)
    {
        $this->app = $app;
        $this->name = $name;
        $this->path = $path;

        $this->fileSystem = $app['files'];
        $this->translator = $app['translator'];
    }

    /**
     * @param string|null $fileName
     *
     * @return McowJson
     */
    public function getFromJson(string $fileName = null): McowJson
    {
        $fileName = $fileName ?? 'config.json';

        return Arr::get($this->configJson, $fileName, function () use ($fileName) {
            return $this->configJson[$fileName] = new McowJson($this->path . '/' . $fileName);
        });
    }

    /**
     * @return string
     */
    public function getLowerName(): string
    {
        return strtolower($this->name);
    }

    /**
     * Delete the current module.
     *
     * @return bool
     */
    public function delete(): bool
    {
        return $this->getFromJson()->getFileSystem()->deleteDirectory($this->path);
    }

    /**
     * @param string $key
     *
     * @return mixed
     */
    public function get(string $key)
    {
        return $this->getFromJson()->get($key);
    }

    /**
     * @return void
     */
    protected function registerFiles(): void
    {
        foreach ($this->get('files') as $file) {
            include $this->path . '/' . $file;
        }
    }

    /**
     * @param string $event
     *
     * @return void
     */
    protected function fireEvent(string $event): void
    {
        $lowerName = strtolower($this->name);

        $this->app['events']->dispatch("{$lowerName}_modules_{$event}", [$this]);
    }

    /**
     * Register a translation file namespace.
     *
     * @param string $path
     * @param string $namespace
     *
     * @return void
     */
    private function loadTranslationsFrom(string $path, string $namespace): void
    {
        $this->translator->addNamespace($namespace, $path);
    }

    /**
     * @return void
     */
    protected function registerTranslation()
    {
        $name = strtolower($this->name);
        $langPath = $this->path . '/Resources/lang';

        if (is_dir($langPath)) {
            $this->loadTranslationsFrom($langPath, $name);
        }
    }

    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerTranslation();
        $this->registerFiles();
        $this->fireEvent('boot');
    }

    /**
     * @return void
     */
    public function registerAliases(): void
    {
        $loader = AliasLoader::getInstance();

        foreach ($this->get('aliases') as $aliasName => $aliasClass) {
            $loader->alias($aliasName, $aliasClass);
        }
    }

    /**
     * @return string
     */
    public function getCachedServicesPath(): string
    {
        if (!is_null(env('VAPOR_MAINTENANCE_MODE'))) {
            return Str::replaceLast(
                'config.php',
                Str::snake($this->name) . '_module.php',
                $this->app->getCachedConfigPath()
            );
        }

        return Str::replaceLast(
            'services.php',
            Str::snake($this->name) . '_module.php',
            $this->app->getCachedServicesPath()
        );
    }

    /**
     * @return void
     */
    public function registerProviders(): void
    {
        (new ProviderRepository($this->app, new Filesystem(), $this->getCachedServicesPath()))->load(
            $this->get('providers')
        );
    }

    /**
     * Register the module.
     *
     * @return void
     */
    public function register(): void
    {
        $this->registerAliases();
        $this->registerProviders();
        $this->registerFiles();
        $this->fireEvent('register');
    }

    /**
     * Handle call __toString.
     *
     * @return string
     */
    public function __toString(): string
    {
        return Str::studly($this->name);
    }
}
