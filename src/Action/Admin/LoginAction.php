<?php

namespace App\Action\Admin;

use App\Domain\Service\Admin;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Routing\RouteContext;
use Symfony\Component\HttpFoundation\Session\Session;

final class LoginAction
{
    /**
     * @var Session
     */
    private $session;
    private $admin;

    public function __construct(Session $session, Admin $admin)
    {
        $this->session = $session;
        $this->admin = $admin;
    }

    public function __invoke(
        ServerRequestInterface $request,
        ResponseInterface $response
    ): ResponseInterface {
        $data = (array)$request->getParsedBody();
        $username = (string)($data['username'] ?? '');
        $password = (string)($data['password'] ?? '');

        // Check user credentials. You may use the database here.
        $loggedin = false;
        $loginUser = $this->admin->find([ 'username' => $username ]);

        if (!empty($loginUser->id)) {
            if ($password == $loginUser->password) {
                $loggedin = true;
            }
        }

        // Clear all flash messages
        $flash = $this->session->getFlashBag();
        $flash->clear();

        // Get RouteParser from request to generate the urls
        $routeParser = RouteContext::fromRequest($request)->getRouteParser();

        if ($loggedin) {
            // Login successfully
            // Clears all session data and regenerates session ID
            $this->session->invalidate();
            $this->session->start();

            $this->session->set('id', $loginUser->id);
            $this->session->set('user_type', 'admin');
            $this->session->set('username', $loginUser->username);

            $flash->set('success', 'Login successfully');

            // Redirect to protected page
            $url = $routeParser->urlFor('a-dashboard');
        } else {
            $flash->set('error', 'Invalid Login Details!');

            // Redirect back to the login page
            $url = $routeParser->urlFor('admin-login');
        }

        return $response->withStatus(302)->withHeader('Location', $url);
    }
}
