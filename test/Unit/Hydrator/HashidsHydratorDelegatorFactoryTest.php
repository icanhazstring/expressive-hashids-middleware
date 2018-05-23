<?php
declare(strict_types=1);

namespace icanhaztests\Hashids\Unit\Hydrator;

use icanhazstring\Hashids\HashidsConfigProvider;
use icanhazstring\Hashids\Hydrator\HashidsHydratorDelegatorFactory;
use icanhazstring\Hashids\Hydrator\HashidsStrategy;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;
use Zend\Hydrator\ArraySerializable;
use Zend\Hydrator\StrategyEnabledInterface;

class HashidsHydratorDelegatorFactoryTest extends TestCase
{
    /**
     * @test
     */
    public function itShouldAddStrategyToHydrator()
    {
        $strategy = $this->prophesize(HashidsStrategy::class);

        $hydrator = new ArraySerializable();

        $container = $this->prophesize(ContainerInterface::class);
        $container->get(HashidsStrategy::class)->shouldBeCalled()->willReturn($strategy);
        $container->get('config')->shouldBeCalled()->willReturn([
            HashidsConfigProvider::CONFIG_KEY => ['resource_identifier' => 'id']
        ]);

        $callback = function () use ($hydrator) {
            return $hydrator;
        };

        $delegator = new HashidsHydratorDelegatorFactory();

        /** @var StrategyEnabledInterface $instance */
        $instance = $delegator($container->reveal(), '', $callback);

        $this->assertTrue($instance->hasStrategy('id'));
    }
}
