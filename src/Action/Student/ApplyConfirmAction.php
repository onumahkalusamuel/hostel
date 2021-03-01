<?php

namespace App\Action\Student;

use App\Domain\Service\Student;
use App\Domain\Service\Room;
use App\Domain\Service\Block;
use App\Domain\Service\Hostel;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Routing\RouteContext;
use Symfony\Component\HttpFoundation\Session\Session;

final class ApplyConfirmAction
{
    /**
     * @var Session
     */
    private $session;
    private $student;
    private $hostel;
    private $block;
    private $room;

    public function __construct(Session $session, Student $student, Hostel $hostel, Block $block, Room $room)
    {
        $this->session = $session;
        $this->student = $student;
	$this->hostel = $hostel;
        $this->block = $block;
        $this->room  = $room;
    }

    public function __invoke(
        ServerRequestInterface $request,
        ResponseInterface $response
    ): ResponseInterface {

        $data = (array)$request->getParsedBody();
        $hostel_id = (int) ($data['hostel_id'] ?? '');
	$student_id = $this->session->get('id');

	if(!empty($hostel_id)) {

            $block = $this->block->readAll(['hostel_id' => $hostel_id]);

            foreach($block as $b) {

                $room = $this->room->readAll(['block_id' => $b->id]);

                foreach($room as $r) {

		    if($r->used < $r->total) {

			$allot = $this->student->update($student_id, ['room' => $r->name, 'room_id'=> $r->id]);

			if($allot) {
			    $this->room->update($r->id, ['used'=> ($r->used + 1)]);
			    $allotted = true;
			    break;
			}
		    }
                    if(!empty($allotted)) break;
                }
		if(!empty($allotted)) break;
	    }
	}

        // Clear all flash messages
        $flash = $this->session->getFlashBag();
        $flash->clear();

        // Get RouteParser from request to generate the urls
        $routeParser = RouteContext::fromRequest($request)->getRouteParser();

	$url = $routeParser->urlFor('s-apply');

       if (!empty($allotted)) {
            $flash->set('success', 'Room Alloted successfully.');
        } else {
            $flash->set('error', 'Unable to process allotment at the moment. Please try again later.');
        }

        return $response->withStatus(302)->withHeader('Location', $url);
    }
}
