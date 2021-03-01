<?php

namespace App\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Slim\Psr7\Response as Psr7Response;
use Slim\Routing\RouteContext;
use Symfony\Component\HttpFoundation\Session\Session;

final class StudentAuthMiddleware implements MiddlewareInterface
{
    /**
     * @var Session
     */
    private $session;

    public function __construct(Session $session)
    {
        $this->session = $session;
    }

    public function process(
        ServerRequestInterface $request,
        RequestHandlerInterface $handler
    ): ResponseInterface {
        if ($this->session->get('user_type') === "student") {
            // User is logged in
            return $handler->handle($request);
        }

        // User is not logged in. Redirect to login page.
        $routeParser = RouteContext::fromRequest($request)->getRouteParser();

        $url = $routeParser->urlFor('student-login');

        $response = new Psr7Response();

        return $response->withStatus(302)->withHeader('Location', $url);
    }
}
