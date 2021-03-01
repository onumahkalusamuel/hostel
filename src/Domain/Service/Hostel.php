<?php

namespace App\Domain\Service;

use App\Domain\Repository\HostelRepository as Repo;
use App\Base\Domain\Service;

class Hostel extends Service {

    protected $repository;

    public function __construct(Repo $repository) {
	$this->repository = $repository;
   }

}
