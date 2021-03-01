<?php

namespace App\Base\Domain;

use Illuminate\Database\Connection;

class Repository
{
    /**
     * @var PDO The database connection
     */
    protected $connection;

    protected $table = '';

    protected $properties = [];
    /**
     * Constructor.
     *
     * @param PDO $connection The database connection
     */
    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    public function readSingle(int $id, array $select= ['*']): object
    {
        $__ = $this->connection->table($this->table)->select($select);
        return (object) $__->find($id);
    }

    public function readPaging($params = [], $filter = [], $select = ['*'])
    {
	$return = ['data'=>[], 'total_rows'=>0];

        $__ = $this->connection
            ->table($this->table)
            ->select($select);

	// where like
	if(!empty($params['like'])) {

	   $__->where(function ($q) use ($params) {

		$x = 0;

		foreach($params['like'] as $key => $value) {

		    if($x==0) $q->where($key, 'LIKE', '%'.$value.'%');
		    else $q->orWhere($key, 'LIKE', '%'.$value.'%');

		    $x++;
		}
	   });
	}

	// where direct
	if(!empty($params['where'])) {
	    $__->where($params['where']);
	}

	// get the count first
	$return['total_rows'] = $__->get()->count();

	// then continue
        // order
	$order_by = $filter['sort_by'] && in_array($filter['sort_by'], $this->properties)
		? $filter['sort_by']
		: 'id';
	$desc = $filter['desc'] ? 'DESC' : 'ASC';

        $__->orderBy($order_by, $desc);

        // records per page
        if (!empty($filter['rpp'])) {
            // offset
            $__->skip($filter['offset']);
            $__->take($filter['rpp']);
        }

        $return['data'] =  $__->get()->all();

	// finally return
	return $return;

    }

    public function totalRows($params = []): int
    {

         $__ = $this->readPaging($params, [], ['id']);

        return (int) count($__);
    }

    public function readAll($params = [])
    {
        $__ = $this->connection->table($this->table)
            ->select(['*']);

	// where
        if(!empty($params)) {
            $__->where($params);
        }

        return (array) $__->get()->all();
    }

    public function find(array $params): object
    {
        return (object) $this->connection->table($this->table)->where($params)->get()->first();
    }

    public function create(array $data): int
    {
        $row = [];
        foreach ($data as $key => $value)
            if (in_array($key, $this->properties))
                $row[$key] = $value;

        return $this->connection->table($this->table)->insertGetId($row);
    }

    public function delete(int $id): bool
    {
        return $this->connection->table($this->table)
            ->where(['id' => $id])
            ->delete();
    }

    public function update(int $id, array $data): bool
    {
        $row = [];
        foreach ($data as $key => $value)
            if (in_array($key, $this->properties) && !in_array($key, ['id']))
                $row[$key] = $value;

	$up = $this->connection->table($this->table)
            ->where(['id' => $id])
            ->update($row);

	return true;
    }
}
