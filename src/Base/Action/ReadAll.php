<?php

namespace App\Base\Action;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class ReadPaging
{

    protected $handle;
    protected $whereQueryLike = [];
    protected $where = [];
    protected $select = ["*"];

    public function __invoke(
        ServerRequestInterface $request,
        ResponseInterface $response,
        $args
    ) {

	$query = !empty($_GET['query']) ? $_GET['query'] : "";
	$filter = $params = [];

	// build search terms
	// whereLike
	if(!empty($query) && !empty($this->whereQueryLike)) {
	    foreach($this->whereQueryLike as $wql)
		$params['like'][$wql] = $query;
	}

	// where
	if(!empty($query) && !empty($this->where)) {
            foreach($this->where as $w)
                $params['where'][$w] = $query;
        }

	// paging
        $filter['page'] = !empty($_GET['page']) ? $_GET['page'] : 1;
        $filter['rpp'] = isset($_GET['rpp']) ? (int) $_GET['rpp'] : 20;

	// sorting
	$filter['sory_by'] = !empty($_GET['sort_by']) ? $_GET['sort_by'] : 'id';
        $filter['desc'] = $_GET['desc'] == 'true';

        $read = $this->handle->readPaging($params, $filter, $this->select);

	// return
        $response->getBody()->write(json_encode($read));

        return $response;
    }
}
