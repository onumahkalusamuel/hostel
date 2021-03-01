<?php

namespace App\Domain\Repository;

use App\Base\Domain\Repository;

class BlockAdminRepository extends Repository
{
    protected $connection;
    protected $table = 'block_admin';
    protected $properties = [ 'hostel', 'hostel_id', 'block', 'block_id', 'name', 'phone', 'position', 'show_to_student' ];

}
