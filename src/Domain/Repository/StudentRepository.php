<?php

namespace App\Domain\Repository;

use Illuminate\Database\Connection;
use App\Base\Domain\Repository;

class StudentRepository extends Repository
{
    protected $connection;
    protected $table = 'student';
    protected $properties = [ 'name', 'gender', 'admission_no', 'college', 'department', 'level', 'room', 'room_id', 'username', 'password' ];

}
