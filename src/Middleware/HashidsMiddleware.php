<?php
declare(strict_types=1);

namespace icanhazstring\Hashids\Middleware;

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
    public const ATTRIBUTE = '__hashids_identifier';

    /** @var HashidsInterface */
    private $service;
    /** @var string */
    private $resourceIdentifier;

    /**
     * @param HashidsInterface $service
     * @param string           $resourceIdentifier
     */
    public function __construct(HashidsInterface $service, string $resourceIdentifier)
    {
        $this->service = $service;
        $this->resourceIdentifier = $resourceIdentifier;
    }

    /**
     * @inheritDoc
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $requestAttributes = $request->getAttributes();

        if (isset($requestAttributes[$this->resourceIdentifier])) {
            $value = $requestAttributes[$this->resourceIdentifier];
            $request = $request->withAttribute(
                self::ATTRIBUTE,
                $this->service->decode($value)[0] ?? $value
            );
        }

        return $handler->handle($request);
    }
}
