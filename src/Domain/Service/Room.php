<?php

namespace App\Domain\Service;

use App\Domain\Repository\RoomRepository as Repo;
use App\Base\Domain\Service;

class Room extends Service {

    protected $repository;

    public function __construct(Repo $repository) {
	$this->repository = $repository;
   }

}
