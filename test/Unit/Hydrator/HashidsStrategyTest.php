<?php
declare(strict_types=1);

namespace icanhaztests\Hashids\Unit\Hydrator;

use Hashids\HashidsInterface;
use icanhazstring\Hashids\Hydrator\HashidsStrategy;
use PHPUnit\Framework\TestCase;

/**
 * @package icanhaztests\Hashids\Unit\Hydrator
 * @author  icanhazstring <blubb0r05+github@gmail.com>
 */
class HashidsStrategyTest extends TestCase
{
    /**
     * @test
     */
    public function itShouldEncodeOnExtraction()
    {
        $hashids = $this->prophesize(HashidsInterface::class);
        $hashids->encode(1)->shouldBeCalled()->willReturn('ABC');

        $strategy = new HashidsStrategy($hashids->reveal());

        $this->assertSame('ABC', $strategy->extract(1));
    }

    /**
     * @test
     */
    public function itShouldDecodeOnHydration()
    {
        $hashids = $this->prophesize(HashidsInterface::class);
        $hashids->decode('ABC')->shouldBeCalled()->willReturn([1]);

        $strategy = new HashidsStrategy($hashids->reveal());

        $this->assertSame(1, $strategy->hydrate('ABC'));
    }
}
