<?php
declare(strict_types=1);

namespace icanhazstring\Hashids\Hydrator;

use Hashids\HashidsInterface;
use Zend\Hydrator\Strategy\StrategyInterface;

/**
 * @package icanhazstring\Hashids\Hydrator
 * @author  icanhazstring <blubb0r05+github@gmail.com>
 */
class HashidsStrategy implements StrategyInterface
{
    private $service;

    /**
     * @param HashidsInterface $service
     */
    public function __construct(HashidsInterface $service)
    {
        $this->service = $service;
    }

    /**
     * @inheritDoc
     */
    public function extract($value)
    {
        return $this->service->encode($value);
    }

    /**
     * @inheritDoc
     */
    public function hydrate($value)
    {
        return $this->service->decode($value)[0] ?? $value;
    }
}
