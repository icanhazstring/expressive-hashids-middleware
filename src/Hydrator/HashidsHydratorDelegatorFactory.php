<?php
declare(strict_types=1);

namespace icanhazstring\Hashids\Hydrator;

use icanhazstring\Hashids\HashidsConfigProvider;
use Psr\Container\ContainerInterface;
use Zend\Hydrator\AbstractHydrator;

/**
 * @package icanhazstring\Hashids
 * @author  icanhazstring <blubb0r05+github@gmail.com>
 */
class HashidsHydratorDelegatorFactory
{
    /**
     * @param ContainerInterface $container
     * @param string             $name
     * @param callable           $callback
     *
     * @return AbstractHydrator
     */
    public function __invoke(ContainerInterface $container, string $name, callable $callback): AbstractHydrator
    {
        $instance = $callback();

        $config = $container->get('config')[HashidsConfigProvider::CONFIG_KEY] ?? null;

        if ($config && $instance instanceof AbstractHydrator) {
            $instance->addStrategy(
                $config['resource_identifier'],
                $container->get(HashidsStrategy::class)
            );
        }

        return $instance;
    }
}
