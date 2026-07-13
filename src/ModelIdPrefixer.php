<?php

declare(strict_types=1);

namespace Netsells\HashModelIds;

use Illuminate\Database\Eloquent\Model;

class ModelIdPrefixer implements ModelIdHasherInterface
{
    public const DEFAULT_PREFIX = 'id_';

    public function __construct(private readonly string $prefix = self::DEFAULT_PREFIX)
    {
        //
    }

    /**
     * @param class-string<Model>|Model $model
     */
    public function encode(string|Model $model, $id): string
    {
        return "{$this->prefix}{$id}";
    }

    /**
     * @param class-string<Model>|Model $model
     */
    public function decode(string|Model $model, $hash): string
    {
        return substr($hash, strlen($this->prefix));
    }
}
