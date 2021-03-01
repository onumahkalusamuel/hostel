<?php

namespace App\Domain\Service;

use App\Domain\Repository\BlockRepository as Repo;
use App\Base\Domain\Service;

class Block extends Service {

    protected $repository;

    public function __construct(Repo $repository) {
	$this->repository = $repository;
   }

}
