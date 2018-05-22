<?php
declare(strict_types=1);

namespace icanhaztests\Middleware\Unit\Service;

use Hashids\HashidsInterface;
use icanhazstring\Middleware\Exception\InvalidConfigException;
use icanhazstring\Middleware\Service\HashidsFactory;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;
use Zend\Diactoros\ServerRequest;

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
            HashidsFactory::CONFIG_KEY => [
                'salt' => '',
                'minHashLength' => 0,
                'alphabet' => 'abcdefghijklmnopqrstuvwxyz'
            ]
        ]);

        $factory = new HashidsFactory();

        $instance = $factory($container->reveal());
        $this->assertInstanceOf(HashidsInterface::class, $instance);
    }
}