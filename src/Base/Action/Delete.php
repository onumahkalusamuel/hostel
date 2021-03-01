<?php

namespace App\Base\Action;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class Delete
{

    protected $handle;

    public function __invoke(
        ServerRequestInterface $request,
        ResponseInterface $response,
        $args
    ) {

	$id = $args['id'];

        $delete = $this->handle->delete($id);
	if($delete) {
	    $response->getBody()->write(json_encode(['success' => true, 'message' => 'Deleted successfully.']));
	    return $response;
	} else {
	    $response->getBody()->write(json_encode(['success' => false, 'message' => 'Unable to delete. Please try again later.']));
	    return $response->withStatus(400);
	}
    }
}
