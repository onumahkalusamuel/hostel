<?php

namespace App\Action\Student;

use App\Domain\Service\Student;
use App\Domain\Service\Room;
use App\Domain\Service\Block;
use App\Domain\Service\Hostel;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Views\Twig;
use Symfony\Component\HttpFoundation\Session\Session;

class ApplyAction
{
    private $twig;
    private $hostel;
    private $block;
    private $room;
    private $student;
    private $session;

    public function __construct(Session $session, Twig $twig, Hostel $hostel, Block $block, Room $room, Student $student)
    {
	$this->session = $session;
        $this->twig = $twig;
	$this->hostel = $hostel;
	$this->block = $block;
	$this->room  = $room;
	$this->student = $student;
    }

    public function __invoke(
        ServerRequestInterface $request,
        ResponseInterface $response,
        $args
    ) {
	$return = [];
	$allotted = false;
	$allotted_details = [];
	$student_id = $this->session->get('id');

	$student = $this->student->readSingle($student_id);

	if(!empty($student->room_id)) {

	    $room = $this->room->readSingle($student->room_id);
	    $block = $this->block->readSingle($room->block_id);
	    $hostel = $this->hostel->readSingle($block->hostel_id);
	    $allotted = true;
	    $allotted_details = [
		'room' => $room->name,
		'block' => $block->name,
		'hostel' => $hostel->name
	    ];
	} else {

	$hostel = $this->hostel->readAll();

	  foreach($hostel as $h) {

	    $return[$h->id] = [ 'id' => $h->id, 'name' => $h->name, 'count' => ['used'=> 0, 'total' => 0 ] ];
	    $block = $this->block->readAll(['hostel_id' => $h->id]);

	    foreach($block as $b) {

		$room = $this->room->readAll(['block_id' => $b->id]);

		foreach($room as $r) {

	 	    $return[$h->id]['count']['used'] += $r->used;
		    $return[$h->id]['count']['total'] += $r->total;

		}
	    }

	  }

	}
        // return $response;
        return $this->twig->render($response, 'student/apply.twig', compact('return', 'allotted', 'allotted_details'));
    }
}
