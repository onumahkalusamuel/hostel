<?php

namespace App\Domain\Repository;

use Illuminate\Database\Connection;
use App\Base\Domain\Repository;

class AdminRepository extends Repository
{
    protected $connection;
    protected $table = 'admin';
    protected $properties = [ 'name', 'username', 'password' ];

}
