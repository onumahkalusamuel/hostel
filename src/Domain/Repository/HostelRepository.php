<?php

namespace App\Domain\Repository;

use App\Base\Domain\Repository;

class HostelRepository extends Repository
{
    protected $connection;
    protected $table = 'hostel';
    protected $properties = [ 'name' ];
}
