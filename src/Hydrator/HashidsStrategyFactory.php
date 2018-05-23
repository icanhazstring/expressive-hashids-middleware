<?php
declare(strict_types=1);

namespace icanhazstring\Hashids\Hydrator;

use Hashids\HashidsInterface;
use icanhazstring\Middleware\Exception\MissingDependencyException;
use Psr\Container\ContainerInterface;
use Zend\Hydrator\Strategy\StrategyInterface;

/**
 * @package icanhazstring\Hashids\Hydrator
 * @author  icanhazstring <blubb0r05+github@gmail.com>
 */
class HashidsStrategyFactory
{
    /**
     * @param ContainerInterface $container
     *
     * @return StrategyInterface
     */
    public function __invoke(ContainerInterface $container): StrategyInterface
    {
        if (!$container->has(HashidsInterface::class)) {
            throw new MissingDependencyException(sprintf(
                'Could not create %s service; dependency %s missing',
                HashidsStrategy::class,
                HashidsInterface::class
            ));
        }

        return new HashidsStrategy($container->get(HashidsInterface::class));
    }
}
