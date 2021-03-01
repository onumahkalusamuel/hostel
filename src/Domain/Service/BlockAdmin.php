<?php

namespace App\Domain\Service;

use App\Domain\Repository\BlockAdminRepository as Repo;
use App\Base\Domain\Service;

class BlockAdmin extends Service {

    protected $repository;

    public function __construct(Repo $repository) {
	$this->repository = $repository;
   }

}
