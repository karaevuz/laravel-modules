<?php
/**
 * Mcow Laravel modules.
 *
 * @author  Muzaffardjan Karaev
 * @link    https://karaev.uz
 * Created: 12.01.2022 / 20:15
 */
declare(strict_types=1);

namespace Mcow\LaravelModules\Extensions;

use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Collection;

/**
 * Class McowJson
 * @package Mcow\LaravelModules\Extensions
 */
class McowJson
{
    /** @var string */
    protected string $path;

    /** @var Filesystem */
    protected $fileSystem;

    /** @var Collection */
    protected $attributes;

    /**
     * McowJson constructor.
     * @param string $path
     */
    public function __construct(string $path)
    {
        $this->path = $path;
        $this->fileSystem = new Filesystem();
        $this->attributes = Collection::make($this->getAttributes());
    }

    /**
     * @return string
     */
    public function getPath(): string
    {
        return $this->path;
    }

    /**
     * @return Filesystem
     */
    public function getFileSystem(): Filesystem
    {
        return $this->fileSystem;
    }

    /**
     * @return array
     */
    public function getAttributes(): array
    {
        $attributes = [];

        try {
            $attributes = json_decode($this->getFileSystem()->get($this->getPath()));

            if (json_last_error() > 0) {
                throw new \RuntimeException(
                    'Error processing file: ' . $this->getPath() . '. Error: ' . json_last_error_msg()
                );
            }

            if (config('modules.cache.enabled')) {
                $lifetime = config('modules.cache.lifetime');

                return app('cache')->remember($this->getPath(), $lifetime, function () use ($attributes) {
                    return $attributes;
                });
            }
        } catch (\Exception $exception) {

        }

        return $attributes;
    }

    /**
     * @param string $key
     * @return mixed
     */
    public function get(string $key)
    {
        return $this->attributes->get($key);
    }

    /**
     * @param string $path
     * @return McowJson
     */
    public static function make(string $path): McowJson
    {
        return new static($path);
    }

    /**
     * Handle magic method __get.
     *
     * @param string $key
     *
     * @return mixed
     */
    public function __get(string $key)
    {
        return $this->get($key);
    }

    /**
     * Handle call to __call method.
     *
     * @param string $method
     * @param array  $arguments
     *
     * @return mixed
     */
    public function __call(string $method, array $arguments = [])
    {
        if (method_exists($this, $method)) {
            return call_user_func_array([$this, $method], $arguments);
        }

        return call_user_func_array([$this->attributes, $method], $arguments);
    }

    /**
     * Handle call to __toString method.
     *
     * @return string
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    public function __toString(): string
    {
        return $this->getFileSystem()->get($this->getPath());
    }
}
