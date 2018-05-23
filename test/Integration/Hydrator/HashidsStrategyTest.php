<?php
declare(strict_types=1);

namespace icanhaztests\Hashids\Integration\Hydrator;

use Hashids\HashidsInterface;
use icanhazstring\Hashids\Hydrator\HashidsStrategy;
use PHPUnit\Framework\TestCase;
use Zend\Hydrator\ArraySerializable;
use Zend\Stdlib\ArrayObject;

class HashidsStrategyTest extends TestCase
{
    /**
     * @test
     */
    public function itShouldEncodeValueOnExtraction()
    {
        $hashids = $this->prophesize(HashidsInterface::class);
        $hashids->encode(1)->shouldBeCalled()->willReturn('ABC');

        $hydrator = new ArraySerializable();
        $hydrator->addStrategy('id', new HashidsStrategy($hashids->reveal()));

        $object = new ArrayObject(['id' => 1]);

        $this->assertSame(['id' => 'ABC'], $hydrator->extract($object));
    }

    /**
     * @test
     */
    public function itShouldDecodeValueOnHydration()
    {
        $hashids = $this->prophesize(HashidsInterface::class);
        $hashids->decode('ABC')->shouldBeCalled()->willReturn([1]);

        $hydrator = new ArraySerializable();
        $hydrator->addStrategy('id', new HashidsStrategy($hashids->reveal()));

        /** @var ArrayObject $object */
        $object = $hydrator->hydrate(['id' => 'ABC'], new ArrayObject([], ArrayObject::ARRAY_AS_PROPS));
        $this->assertSame(1, $object->id);
    }
}
