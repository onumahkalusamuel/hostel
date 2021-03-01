<?php

namespace App\Domain\Service;

use App\Domain\Repository\SettingsRepository as Repo;
use App\Base\Domain\Service;

class Settings extends Service {

    protected $repository;

    public function __construct(Repo $repository) {
	$this->repository = $repository;
   }

   public function __get($setting) {
	return $this->find(['setting'=>$setting])->value;
   }

   public function __set($setting, $value) {
	return $this->repository->update($setting, $value);
   }

}
