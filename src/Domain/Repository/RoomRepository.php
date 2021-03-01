<?php

namespace App\Domain\Repository;

use App\Base\Domain\Repository;

class RoomRepository extends Repository
{
    protected $connection;
    protected $table = 'room';
    protected $properties = [ 'block_id', 'hostel_id', 'name', 'total', 'used', 'reserved' ];

}
