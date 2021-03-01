<?php

namespace App\Action\Admin\BlockAdmin;

use App\Domain\Service\BlockAdmin;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Routing\RouteContext;
use Symfony\Component\HttpFoundation\Session\Session;

class DeleteAction
{
    private $blockAdmin;
    private $session;

    public function __construct(Session $session, BlockAdmin $blockAdmin)
    {
        $this->blockAdmin = $blockAdmin;
        $this->session = $session;
    }

    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, $args)
    {
        if (!empty($args['id'])) {
            $delete = $this->blockAdmin->delete($args['id']);
        }

        // Clear all flash messages
        $flash = $this->session->getFlashBag();
        $flash->clear();

        // Get RouteParser from request to generate the urls
        $routeParser = RouteContext::fromRequest($request)->getRouteParser();

        if (!empty($delete)) {
            $flash->set('success', 'Record deleted successfully.');
        } else {
            $flash->set('error', 'Unable to delete record at the moment.');
        }

        $url = $routeParser->urlFor('a-block-admin') . '?' . http_build_query($_GET);

        return $response->withStatus(302)->withHeader('Location', $url);
    }
}
