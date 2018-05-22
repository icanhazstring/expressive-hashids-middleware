<?php
declare(strict_types=1);

namespace icanhazstring\Middleware\Service;

use Hashids\Hashids;
use Hashids\HashidsInterface;
use icanhazstring\Middleware\Exception\InvalidConfigException;
use Psr\Container\ContainerInterface;
use function is_null;
use function sprintf;

/**
 * @package icanhazstring\Middleware
 * @author  icanhazstring <blubb0r05+github@gmail.com>
 */
class HashidsFactory
{
    public const CONFIG_KEY = 'hashids_service';

    /**
     * @param ContainerInterface $container
     *
     * @return HashidsInterface
     */
    public function __invoke(ContainerInterface $container): HashidsInterface
    {
        /** @var array $config */
        $config = $container->get('config')[self::CONFIG_KEY] ?? null;

        if (is_null($config)) {
            throw new InvalidConfigException(sprintf(
                'Missing %s config for %s service',
                self::CONFIG_KEY,
                HashidsInterface::class
            ));
        }

        return new Hashids($config['salt'], $config['minHashLength'], $config['alphabet']);
    }
}
