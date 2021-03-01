<?php

namespace App\Action\Admin\Hostel;

use App\Domain\Service\Hostel;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Views\Twig;

class IndexAction
{
    private $twig;
    private $hostel;

    public function __construct(Twig $twig, Hostel $hostel)
    {
        $this->twig = $twig;
	$this->hostel = $hostel;
    }

    public function __invoke(
        ServerRequestInterface $request,
        ResponseInterface $response,
        $args
    ) {

	$hostel = (array) $this->hostel->readAll();

        // return $response;
        return $this->twig->render($response, 'admin/hostel/index.twig', compact('hostel'));
    }
}
