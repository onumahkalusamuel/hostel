<?php

namespace App\Base\Action;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class Read
{

    protected $handle;
    protected $select = ['*'];
    protected $intProperties = [];
    protected $floatProperties = [];
    protected $boolProperties = [];

    public function __invoke(
        ServerRequestInterface $request,
        ResponseInterface $response,
        $args
    ) {

	$id = $args['id'];
	if(empty($id)) $id = $_GET['id'];

        $read = $this->handle->readSingle($id, $select);

	if(!empty((array)$read)) {
	    //intProperties
	    foreach($this->intProperties as $prop) {
		if(isset($read->$prop)) $read->$prop = (int) $read->$prop;
	    }

	    //floatProperties
	    foreach($this->floatProperties as $prop) {
                if(isset($read->$prop)) $read->$prop = (float) $read->$prop;
            }

	    // boolProperties
	    foreach($this->boolProperties as $prop) {
                if(isset($read->$prop)) $read->$prop = (bool) $read->$prop;
            }
	}

        $response->getBody()->write(json_encode($read));
        return $response;
    }
}
