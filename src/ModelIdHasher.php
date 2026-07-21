<?php

declare(strict_types=1);

namespace Netsells\HashModelIds;

use Hashids\Hashids;
use Illuminate\Database\Eloquent\Model;
use InvalidArgumentException;

class ModelIdHasher implements ModelIdHasherInterface
{
    private array $config;

    private array $instances;

    public function __construct(array $config)
    {
        if (empty($config['salt'] ?? '')) {
            throw new InvalidArgumentException('A hashids salt must be set!');
        }

        $this->config = $config;
    }

    /**
     * @param class-string<Model>|Model $model
     */
    public function encode(string|Model $model, $id): string
    {
        return $this->getInstance($model)->encode($id);
    }

    /**
     * @param class-string<Model>|Model $model
     */
    public function decode(string|Model $model, $hash): string
    {
        return implode('', $this->getInstance($model)->decode($hash));
    }

    /**
     * @param class-string<Model>|Model $model
     */
    private function getInstance(string|Model $model): Hashids
    {
        $class = is_string($model) ? $model : $model::class;

        if (! is_a($class, Model::class, true)) {
            throw new InvalidArgumentException('The given class must be an instance of '.Model::class);
        }

        if (! isset($this->instances[$class])) {
            $this->instances[$class] = $this->getNewInstance($class);
        }

        return $this->instances[$class];
    }

    private function getNewInstance(string $class): Hashids
    {
        return new Hashids(
            $class.$this->config['salt'],
            $this->config['min_hash_length'],
            $this->config['alphabet'],
        );
    }
}
