<?php

namespace App\Base\Action;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class UpdateAction
{

    protected $handle;

    public function __invoke(
        ServerRequestInterface $request,
        ResponseInterface $response,
        $args
    ) {

	$data = $request->getParsedBody();
	$id = $args['id'];
        $update = $this->handle->update($id, $data);

	if(empty($update)) {
	    $response->getBody()->write(json_encode(['success'=> false, 'message'=> 'Unable to update record at the moment']));
	    return $response->withStatus(400);
	}

	$response->getBody()->write(json_encode(['success'=>true, 'message'=> 'Update successful']));
	return $response;

    }
}
