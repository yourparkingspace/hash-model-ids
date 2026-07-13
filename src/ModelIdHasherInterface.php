<?php

declare(strict_types=1);

namespace Netsells\HashModelIds;

use Illuminate\Database\Eloquent\Model;

interface ModelIdHasherInterface
{
    /**
     * @param class-string<Model>|Model $model
     */
    public function encode(string|Model $model, $id): string;

    /**
     * @param class-string<Model>|Model $model
     */
    public function decode(string|Model $model, $hash): string;
}
