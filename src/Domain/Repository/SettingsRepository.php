<?php

namespace App\Domain\Repository;

use App\Base\Domain\Repository;

/**
 * Repository.
 */
class SettingsRepository extends Repository
{
    /**
     * @var PDO The database connection
     */
    protected $connection;
    protected $table = 'settings';
    protected $properties = [
	'setting',
	'value'
    ];

    public function update($Setting, $Value): bool {
	return (bool) $this->connection->table($this->table)
            ->where(['setting' => $Setting])
            ->update(['value' => $Value]);
    }

}
