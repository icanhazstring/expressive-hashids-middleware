<?php
declare(strict_types=1);

namespace icanhaztests\Hashids\Unit\Middleware;

use Hashids\HashidsInterface;
use icanhazstring\Hashids\Middleware\HashidsMiddleware;
use icanhazstring\Hashids\Middleware\HashidsMiddlewareFactory;
use icanhazstring\Middleware\Exception\InvalidConfigException;
use icanhazstring\Middleware\Exception\MissingDependencyException;
use icanhazstring\Hashids\HashidsConfigProvider;
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
            HashidsConfigProvider::CONFIG_KEY => ['resource_identifier' => 'id']
        ]);

        $factory = new HashidsMiddlewareFactory();
        $instance = $factory($container->reveal());

        $this->assertInstanceOf(HashidsMiddleware::class, $instance);
    }
}
