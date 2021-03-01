<?php

namespace App\Action\Admin\Hostel;

use App\Domain\Service\Hostel;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Routing\RouteContext;
use Symfony\Component\HttpFoundation\Session\Session;

class DeleteAction
{
    private $hostel;
    private $session;

    public function __construct(Session $session, Hostel $hostel)
    {
        $this->hostel = $hostel;
        $this->session = $session;
    }

    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, $args)
    {
        if (!empty($args['id'])) {
            $delete = $this->hostel->delete($args['id']);
        }

        // Clear all flash messages
        $flash = $this->session->getFlashBag();
        $flash->clear();

        // Get RouteParser from request to generate the urls
        $routeParser = RouteContext::fromRequest($request)->getRouteParser();

        if (!empty($delete)) {
            $flash->set('success', 'Hostel deleted successfully.');
        } else {
            $flash->set('error', 'Unable to delete hostel at the moment.');
        }

        $url = $routeParser->urlFor('a-hostel');

        return $response->withStatus(302)->withHeader('Location', $url);
    }
}
