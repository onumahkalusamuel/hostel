<?php

namespace App\Action\Admin\Room;

use App\Domain\Service\Room;
use App\Domain\Service\Block;
use App\Domain\Service\Hostel;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Views\Twig;

class IndexAction
{
    private $twig;
    private $hostel;
    private $block;
    private $room;

    public function __construct(Twig $twig, Hostel $hostel, Block $block, Room $room)
    {
        $this->twig = $twig;
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
	$blockName = "";

	$block_id = $_GET['block_id'] ?? null;

	if(!empty($block_id)) {
	    $block = $this->block->readSingle($block_id);
	    $blockName = $block->name;
	    $hostelName = $this->hostel->readSingle($block->hostel_id)->name;
	    $room = (array) $this->room->readAll(['block_id'=>$block_id]);
	}

        // return $response;
        return $this->twig->render($response, 'admin/room/index.twig', compact('room', 'blockName', 'hostelName'));
    }
}
