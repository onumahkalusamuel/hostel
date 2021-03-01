<?php

namespace App\Action\Admin\Hostel;

use App\Domain\Service\Hostel;
use App\Domain\Service\Block;
use App\Domain\Service\Room;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Routing\RouteContext;
use Symfony\Component\HttpFoundation\Session\Session;

class CreateAction
{
    private $hostel;
    private $block;
    private $room;
    private $session;

    public function __construct(Hostel $hostel, Block $block, Room $room, Session $session)
    {
        $this->hostel = $hostel;
        $this->block = $block;
	$this->room = $room;
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
        $url = $routeParser->urlFor('a-hostel');

	// process form
        $data = (array) $request->getParsedBody();
	extract($data);

	//check if it's existing
	$check = $this->hostel->find(compact('name'));

	if(empty($check->id)) {
	    $h_id = $this->hostel->create(compact('name'));

	    if(!empty($h_id)) {
		$flash->set('success', 'Hostel created successfully.');

		// blocks
		for($i = 1; $i <= $blocks; $i++) {

		    $b_id = $this->block->create([

			'hostel' => $name,
			'hostel_id' => $h_id,
			'name' => "Block {$i}",
			'total_rooms' => (int) $rooms
		    ]);

		    if(!empty($b_id)) {

			for($j = 1; $j <= (int) $rooms; $j++) {
			    $this->room->create([
				'block_id' => $b_id,
				'hostel_id' => $h_id,
				'name' => "Room #{$j}",
				'total' => (int) $occupants
			    ]);

			}

		    }

		}
	    } else {
		$flash->set('error', 'Unable to create hostel at the moment.');
	    }
	}

        return $response->withStatus(302)->withHeader('Location', $url);
    }

}
