<?php

namespace App\Action\Admin\Block;

use App\Domain\Service\Block;
use App\Domain\Service\Hostel;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Views\Twig;

class IndexAction
{
    private $twig;
    private $block;
    private $hostel;

    public function __construct(Twig $twig, Block $block, Hostel $hostel)
    {
        $this->twig = $twig;
	$this->block = $block;
	$this->hostel = $hostel;
    }

    public function __invoke(
        ServerRequestInterface $request,
        ResponseInterface $response,
        $args
    ) {
	$block = $hostel = [];
	$hostelName = "";

	$hostel_id = $_GET['hostel_id'] ?? null;

	$hostel = (array) $this->hostel->readAll();

	if(!empty($hostel_id)) {
	    $hostelName = $this->hostel->readSingle($hostel_id)->name;
	    $block = (array) $this->block->readAll(['hostel_id'=>$hostel_id]);
	}

        // return $response;
        return $this->twig->render($response, 'admin/block/index.twig', compact('hostel', 'block', 'hostelName'));
    }
}
