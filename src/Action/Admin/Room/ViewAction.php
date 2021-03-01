<?php

namespace App\Action\Admin\Room;

use App\Domain\Service\Room;
use App\Domain\Service\Block;
use App\Domain\Service\Hostel;
use App\Domain\Service\Student;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Views\Twig;

class ViewAction
{
    private $twig;
    private $student;
    private $hostel;
    private $block;
    private $room;

    public function __construct(Twig $twig, Student $student, Hostel $hostel, Block $block, Room $room)
    {
        $this->twig = $twig;
	$this->student = $student;
	$this->hostel = $hostel;
	$this->block = $block;
	$this->room  = $room;
    }

    public function __invoke(
        ServerRequestInterface $request,
        ResponseInterface $response,
        $args
    ) {

	$room = [];
	$hostelName = $blockName = $roomName = "";

	$room_id = $args['id'] ?? null;

	if(!empty($room_id)) {
	    $room = $this->room->readSingle($room_id);

	    $block = $this->block->readSingle($room->block_id);
	    $blockName = $block->name;
	    $hostelName = $this->hostel->readSingle($block->hostel_id)->name;

	    //get students who were allocated room
	    $student = (array) $this->student->readAll(['room_id' => $room_id]);
	}

        // return $response;
        return $this->twig->render($response, 'admin/room/view-room.twig', compact('room', 'blockName', 'hostelName', 'student'));
    }
}
