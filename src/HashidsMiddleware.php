<?php
declare(strict_types=1);

namespace icanhazstring\Middleware;

use Hashids\HashidsInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

/**
 * @package icanhazstring\Middleware
 * @author  icanhazstring <blubb0r05+github@gmail.com>
 */
class HashidsMiddleware implements MiddlewareInterface
{
    /** @var HashidsInterface */
    private $service;
    /** @var string[] */
    private $attributes;

    /**
     * @param HashidsInterface $service
     * @param string[]         $attributes
     */
    public function __construct(HashidsInterface $service, array $attributes)
    {
        $this->service = $service;
        $this->attributes = $attributes;
    }

    /**
     * @inheritDoc
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $requestAttributes = $request->getAttributes();

        foreach ($this->attributes as $decodeAttribute) {

            if (!isset($requestAttributes[$decodeAttribute])) {
                continue;
            }

            $value = $requestAttributes[$decodeAttribute];
            $request = $request->withAttribute($decodeAttribute, $this->service->decode($value));
        }

        return $handler->handle($request);
    }
}