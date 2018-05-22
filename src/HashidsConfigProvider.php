<?php
declare(strict_types=1);

namespace icanhazstring\Middleware;

use Hashids\HashidsInterface;
use icanhazstring\Middleware\Service\HashidsFactory;

/**
 * @package icanhazstring\Middleware
 * @author  icanhazstring <blubb0r05+github@gmail.com>
 */
class HashidsConfigProvider
{
    /**
     * @return array
     */
    public function __invoke(): array
    {
        return [
            'dependencies' => $this->getDependencies(),
            HashidsFactory::CONFIG_KEY => $this->getHashidsConfig(),
            HashidsMiddlewareFactory::CONFIG_KEY => $this->getMiddlewareConfig()
        ];
    }

    /**
     * @return array
     */
    public function getDependencies(): array
    {
        return [
            'factories' => [
                HashidsInterface::class => HashidsFactory::class,
                HashidsMiddleware::class => HashidsMiddlewareFactory::class
            ]
        ];
    }

    /**
     * @return array
     */
    public function getHashidsConfig(): array
    {
        return [
            'salt' => '',
            'minHashLength' => 0,
            'alphabet' => 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890',
        ];
    }

    /**
     * @return array
     */
    public function getMiddlewareConfig(): array
    {
        return [
            'attributes' => ['id']
        ];
    }
}
