<?php
declare(strict_types=1);

namespace icanhaztests\Hashids\Unit\Hydrator;

use Hashids\HashidsInterface;
use icanhazstring\Hashids\Hydrator\HashidsStrategy;
use icanhazstring\Hashids\Hydrator\HashidsStrategyFactory;
use icanhazstring\Middleware\Exception\MissingDependencyException;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;

/**
 * @package icanhaztests\Hashids\Unit\Hydrator
 * @author  icanhazstring <blubb0r05+github@gmail.com>
 */
class HashidsStrategyFactoryTest extends TestCase
{
    /**
     * @test
     */
    public function itShouldRaiseExceptionOnMissingDependency()
    {
        $container = $this->prophesize(ContainerInterface::class);
        $container->has(HashidsInterface::class)->shouldBeCalled()->willReturn(false);

        $this->expectException(MissingDependencyException::class);
        $factory = new HashidsStrategyFactory();
        $factory($container->reveal());
    }

    /**
     * @test
     */
    public function itShouldCreateProperInstance()
    {
        $hashids = $this->prophesize(HashidsInterface::class);

        $container = $this->prophesize(ContainerInterface::class);
        $container->has(HashidsInterface::class)->shouldBeCalled()->willReturn(true);
        $container->get(HashidsInterface::class)->shouldBeCalled()->willReturn($hashids->reveal());

        $factory = new HashidsStrategyFactory();
        $instance = $factory($container->reveal());

        $this->assertInstanceOf(HashidsStrategy::class, $instance);
    }
}
