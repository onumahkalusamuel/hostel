<?php

namespace App\Domain\Service;

use App\Domain\Repository\StudentRepository as Repo;
use App\Base\Domain\Service;

class Student extends Service {

    protected $repository;

    public function __construct(Repo $repository) {
	$this->repository = $repository;
   }

}
