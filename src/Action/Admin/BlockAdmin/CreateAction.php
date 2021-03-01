<?php

namespace App\Action\Admin\BlockAdmin;

use App\Domain\Service\Hostel;
use App\Domain\Service\Block;
use App\Domain\Service\BlockAdmin;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Routing\RouteContext;
use Symfony\Component\HttpFoundation\Session\Session;

class CreateAction
{
    private $hostel;
    private $block;
    private $blockAdmin;
    private $session;

    public function __construct(Hostel $hostel, Block $block, BlockAdmin $blockAdmin, Session $session)
    {
        $this->hostel = $hostel;
        $this->blockAdmin = $blockAdmin;
	$this->block = $block;
	$this->session = $session;
    }

    public function __invoke(
        ServerRequestInterface $request,
        ResponseInterface $response,
        $args
    ): ResponseInterface {

	// Clear all flash messages
        $flash = $this->session->getFlashBag();
        $flash->clear();
        // Get RouteParser
        $routeParser = RouteContext::fromRequest($request)->getRouteParser();
        $url = $routeParser->urlFor('a-block-admin') . '?' . http_build_query($_GET);

	// process form
        $data = (array) $request->getParsedBody();
	extract($data);

	//check if it's existing
	$check = $this->blockAdmin->find(compact('name'));

	if(empty($check->id)) {
	    $create = $this->blockAdmin->create([
		'name' => $name,
		'block_id' => $block_id,
		'phone' => $phone,
		'position' => $position,
		'show_to_student' => $show_to_student
	    ]);

	    if(!empty($create)) {
		$flash->set('success', 'Block Admin created successfully.');
	    } else {
		$flash->set('error', 'Unable to create Block Admin at the moment.');
	    }
	}

        return $response->withStatus(302)->withHeader('Location', $url);
    }

}
