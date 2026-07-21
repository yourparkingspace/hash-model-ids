<?php

declare(strict_types=1);

namespace Netsells\HashModelIds\Tests\Unit;

use InvalidArgumentException;
use Netsells\HashModelIds\ModelIdHasher;
use Netsells\HashModelIds\Tests\Unit\Fixtures\Models;
use PHPUnit\Framework\TestCase;

class ModelIdHasherTest extends TestCase
{
    private const CONFIG = [
        'salt' => 'test-salt',
        'min_hash_length' => 10,
        'alphabet' => 'abcdefghijklmnopqrstuvwxyz0123456789',
    ];

    public function testInstanceRequiresConfiguration(): void
    {
        $this->expectException(InvalidArgumentException::class);

        $this->getNewModelIdHasher([]);
    }

    public function testHasherCanEncodeAndDecode(): void
    {
        $idHasher = $this->getNewModelIdHasher();

        $model = new Models\Foo();

        $hash = $idHasher->encode($model, 1);

        $this->assertEquals(1, $idHasher->decode($model, $hash));
    }

    public function testHashesDifferForModelsWithSameId(): void
    {
        $idHasher = $this->getNewModelIdHasher();

        $foo = $this->createStub(Models\Foo::class);
        $bar = $this->createStub(Models\Bar::class);

        $this->assertNotSame($idHasher->encode($foo, 1), $idHasher->encode($bar, 1));
    }

    public function testHasherCanEncodeAndDecodeUsingClassString(): void
    {
        $idHasher = $this->getNewModelIdHasher();

        $hash = $idHasher->encode(Models\Foo::class, 1);

        $this->assertEquals(1, $idHasher->decode(Models\Foo::class, $hash));
    }

    public function testEncodingWithClassStringMatchesEncodingWithInstance(): void
    {
        $idHasher = $this->getNewModelIdHasher();

        $model = new Models\Foo();

        $this->assertSame(
            $idHasher->encode($model, 1),
            $idHasher->encode(Models\Foo::class, 1)
        );
    }

    private function getNewModelIdHasher(array $config = self::CONFIG): ModelIdHasher
    {
        return new ModelIdHasher($config);
    }
}
