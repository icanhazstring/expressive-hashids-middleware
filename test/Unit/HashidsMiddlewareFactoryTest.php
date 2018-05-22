<?php
declare(strict_types=1);

namespace icanhaztests\Middleware\Unit;

use Hashids\HashidsInterface;
use icanhazstring\Middleware\HashidsMiddleware;
use icanhazstring\Middleware\HashidsMiddlewareFactory;
use icanhazstring\Middleware\Exception\InvalidConfigException;
use icanhazstring\Middleware\Exception\MissingDependencyException;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;

class HashidsMiddlewareFactoryTest extends TestCase
{
    /**
     * @test
     */
    public function itShouldRaiseExceptionOnMissingDependency()
    {
        $container = $this->prophesize(ContainerInterface::class);
        $container->has(HashidsInterface::class)->shouldBeCalled()->willReturn(false);

        $factory = new HashidsMiddlewareFactory();

        $this->expectException(MissingDependencyException::class);

        $factory($container->reveal());
    }

    /**
     * @test
     */
    public function itShouldRaiseExceptionOnInvalidConfig()
    {
        $container = $this->prophesize(ContainerInterface::class);
        $container->has(HashidsInterface::class)->shouldBeCalled()->willReturn(true);
        $container->get('config')->shouldBeCalled()->willReturn([]);

        $factory = new HashidsMiddlewareFactory();

        $this->expectException(InvalidConfigException::class);

        $factory($container->reveal());
    }

    /**
     * @test
     */
    public function itShouldCreateValidInstance()
    {
        $hashidsService = $this->prophesize(HashidsInterface::class);

        $container = $this->prophesize(ContainerInterface::class);
        $container->has(HashidsInterface::class)->shouldBeCalled()->willReturn(true);
        $container->get(HashidsInterface::class)->shouldBeCalled()->willReturn($hashidsService->reveal());
        $container->get('config')->shouldBeCalled()->willReturn([
            HashidsMiddlewareFactory::CONFIG_KEY => ['attributes' => ['id']]
        ]);

        $factory = new HashidsMiddlewareFactory();
        $instance = $factory($container->reveal());

        $this->assertInstanceOf(HashidsMiddleware::class, $instance);
    }
}