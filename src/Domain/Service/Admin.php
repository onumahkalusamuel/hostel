<?php

namespace App\Domain\Service;

use App\Domain\Repository\AdminRepository as Repo;
use App\Base\Domain\Service;

class Admin extends Service {

    protected $repository;

    public function __construct(Repo $repository) {
	$this->repository = $repository;
   }

}
