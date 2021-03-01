<?php

namespace App\Domain\Repository;

use App\Base\Domain\Repository;

class BlockRepository extends Repository
{
    protected $connection;
    protected $table = 'block';
    protected $properties = [ 'hostel', 'hostel_id', 'name', 'total_rooms', 'reserved' ];

}
