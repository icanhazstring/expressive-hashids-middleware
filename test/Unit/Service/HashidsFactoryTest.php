<?php
declare(strict_types=1);

namespace icanhaztests\Hashids\Unit\Service;

use Hashids\HashidsInterface;
use icanhazstring\Hashids\HashidsConfigProvider;
use icanhazstring\Middleware\Exception\InvalidConfigException;
use icanhazstring\Hashids\Service\HashidsFactory;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;

/**
 * @package icanhaztests\Middleware\Unit\Service
 * @author  icanhazstring <blubb0r05+github@gmail.com>
 */
class HashidsFactoryTest extends TestCase
{
    /**
     * @test
     */
    public function itShouldRaiseExceptionOnInvalidConfig()
    {
        $container = $this->prophesize(ContainerInterface::class);
        $container->get('config')->shouldBeCalled()->willReturn([]);

        $factory = new HashidsFactory();

        $this->expectException(InvalidConfigException::class);
        $factory($container->reveal());
    }

    /**
     * @test
     */
    public function itShouldCreateInstanceWithProperConfig()
    {
        $container = $this->prophesize(ContainerInterface::class);
        $container->get('config')->shouldBeCalled()->willReturn([
            HashidsConfigProvider::CONFIG_KEY => [
                'salt' => '',
                'min_hash_length' => 0,
                'alphabet' => 'abcdefghijklmnopqrstuvwxyz'
            ]
        ]);

        $factory = new HashidsFactory();

        $instance = $factory($container->reveal());
        $this->assertInstanceOf(HashidsInterface::class, $instance);
    }
}
