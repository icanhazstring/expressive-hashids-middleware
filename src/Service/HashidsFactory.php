<?php
declare(strict_types=1);

namespace icanhazstring\Hashids\Service;

use Hashids\Hashids;
use Hashids\HashidsInterface;
use icanhazstring\Middleware\Exception\InvalidConfigException;
use icanhazstring\Hashids\HashidsConfigProvider;
use Psr\Container\ContainerInterface;
use function is_null;
use function sprintf;

/**
 * @package icanhazstring\Hashids\Service
 * @author  icanhazstring <blubb0r05+github@gmail.com>
 */
class HashidsFactory
{
    /**
     * @param ContainerInterface $container
     *
     * @return HashidsInterface
     */
    public function __invoke(ContainerInterface $container): HashidsInterface
    {
        $config = $container->get('config')[HashidsConfigProvider::CONFIG_KEY] ?? null;

        if (is_null($config)) {
            throw new InvalidConfigException(sprintf(
                'Missing %s config for %s service',
                HashidsConfigProvider::CONFIG_KEY,
                HashidsInterface::class
            ));
        }

        return new Hashids($config['salt'], $config['min_hash_length'], $config['alphabet']);
    }
}
