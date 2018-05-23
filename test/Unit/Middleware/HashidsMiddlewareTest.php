<?php
declare(strict_types=1);

namespace icanhaztests\Middleware\Unit;

use Hashids\HashidsInterface;
use icanhazstring\Hashids\Middleware\HashidsMiddleware;
use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Zend\Diactoros\Response\EmptyResponse;
use Zend\Diactoros\ServerRequest;

/**
 * @package icanhaztests\Middleware\Unit
 * @author  icanhazstring <blubb0r05+github@gmail.com>
 */
class HashidsMiddlewareTest extends TestCase
{
    /**
     * @test
     */
    public function itShouldDecodeConfiguredAttributes()
    {
        $self = $this;

        $service = $this->prophesize(HashidsInterface::class);
        $service->decode('ABC')->shouldBeCalled()->willReturn([1]);

        $handler = $this->prophesize(RequestHandlerInterface::class);
        $handler->handle(Argument::any())->will(function ($args) use ($self) {

            /** @var ServerRequestInterface $request */
            $request = $args[0];

            $self->assertSame(1, $request->getAttribute(HashidsMiddleware::ATTRIBUTE));

            return new EmptyResponse();
        });

        $request = (new ServerRequest())->withAttribute('id', 'ABC');
        $middleware = new HashidsMiddleware($service->reveal(), 'id');

        $middleware->process($request, $handler->reveal());
    }

    /**
     * @test
     */
    public function itShouldNotChangeConfiguredAttribute()
    {
        $self = $this;

        $service = $this->prophesize(HashidsInterface::class);
        $service->decode('ABC')->shouldBeCalled()->willReturn([1]);

        $handler = $this->prophesize(RequestHandlerInterface::class);
        $handler->handle(Argument::any())->will(function ($args) use ($self) {
            /** @var ServerRequestInterface $request */
            $request = $args[0];

            $self->assertSame('ABC', $request->getAttribute('id'));

            return new EmptyResponse();
        });

        $request = (new ServerRequest())->withAttribute('id', 'ABC');
        $middleware = new HashidsMiddleware($service->reveal(), 'id');

        $middleware->process($request, $handler->reveal());
    }
}
