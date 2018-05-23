<?php
declare(strict_types=1);

namespace icanhazstring\Hashids;

use Hashids\HashidsInterface;

/**
 * @package icanhazstring\Hashids
 * @author  icanhazstring <blubb0r05+github@gmail.com>
 */
class HashidsConfigProvider
{
    public const CONFIG_KEY = 'hashids_config';

    /**
     * @return array
     */
    public function __invoke(): array
    {
        return [
            'dependencies' => $this->getDependencies(),
            self::CONFIG_KEY => $this->getConfig()
        ];
    }

    /**
     * @return array
     */
    public function getDependencies(): array
    {
        return [
            'factories' => [
                HashidsInterface::class => Service\HashidsFactory::class,
                Hydrator\HashidsStrategy::class => Hydrator\HashidsStrategyFactory::class,
                Middleware\HashidsMiddleware::class => Middleware\HashidsMiddlewareFactory::class
            ]
        ];
    }

    /**
     * @return array
     */
    public function getConfig(): array
    {
        return [
            'salt' => '',
            'min_hash_length' => 0,
            'alphabet' => 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890',
            'resource_identifier' => 'id'
        ];
    }
}
