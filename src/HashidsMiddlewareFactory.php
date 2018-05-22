<?php
declare(strict_types=1);

namespace icanhazstring\Middleware;

use Hashids\HashidsInterface;
use icanhazstring\Middleware\Exception\InvalidConfigException;
use icanhazstring\Middleware\Exception\MissingDependencyException;
use Psr\Container\ContainerInterface;
use function is_null;
use function sprintf;

/**
 * @package icanhazstring\Middleware
 * @author  icanhazstring <blubb0r05+github@gmail.com>
 */
class HashidsMiddlewareFactory
{
    public const CONFIG_KEY = 'hashids_middleware';

    /**
     * @param ContainerInterface $container
     *
     * @return HashidsMiddleware
     */
    public function __invoke(ContainerInterface $container): HashidsMiddleware
    {
        if (!$container->has(HashidsInterface::class)) {
            throw new MissingDependencyException(sprintf(
                'Could not create %s service; dependency %s missing',
                HashidsMiddleware::class,
                HashidsInterface::class
            ));
        }

        $config = $container->get('config')[self::CONFIG_KEY] ?? null;

        if (is_null($config)) {
            throw new InvalidConfigException(sprintf(
                'Missing %s config for %s service',
                self::CONFIG_KEY,
                HashidsMiddleware::class
            ));
        }

        return new HashidsMiddleware($container->get(HashidsInterface::class), $config['attributes']);
    }
}