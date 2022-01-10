<?php
/**
 * Mcow Laravel modules.
 *
 * @author  Muzaffardjan Karaev
 * @link    https://karaev.uz
 * Created: 10.01.2022 / 12:06
 */
declare(strict_types=1);

namespace Mcow\LaravelModules\Extensions;

use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Collection;
use RuntimeException;

/**
 * Class McowJson
 * @package Mcow\LaravelModules\Extensions
 */
class McowJson
{
    /**
     * The file path.
     *
     * @var string
     */
    protected string $path;

    /**
     * The laravel filesystem instance.
     *
     * @var Filesystem
     */
    protected $fileSystem;

    /**
     * The attributes collection.
     *
     * @var \Illuminate\Support\Collection
     */
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
     * @return array
     */
    public function getAttributes(): array
    {
        $attributes = [];

        try {
            $attributes = json_decode($this->fileSystem->get($this->path), true);

            // any JSON parsing errors should throw an exception
            if (json_last_error() > 0) {
                throw new RuntimeException(
                    'Error processing file: ' . $this->path . '. Error: ' . json_last_error_msg()
                );
            }

            if (config('modules.cache.enabled') === false) {
                return $attributes;
            }

            return app('cache')->remember(
                $this->path,
                config('modules.cache.lifetime'),
                function () use ($attributes) {
                    return $attributes;
                }
            );
        } catch (\Exception $exception) {

        }

        return $attributes;
    }

    /**
     * Make new instance.
     *
     * @param string $path
     * @return static
     */
    public static function make(string $path): static
    {
        return new static($path);
    }
}
