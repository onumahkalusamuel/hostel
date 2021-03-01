<?php

namespace App\Base\Domain;

class Service {

    protected $repository;

    public function readSingle(int $id, array $select =['*']): object {
	return $this->repository->readSingle($id, $select);
    }

    public function readPaging(array $params = [], array $filter = [], array $select = ['*']): array
    {
    	if (!empty($filter['rpp'])) {
            $filter['page'] = $filter['page'] ?? 1;
            $filter['offset'] = ($filter['page'] - 1) * $filter['rpp'];
        } else {
            $filter['rpp'] = 0;
            $filter['page'] = 1;
            $filter['offset'] = 0;
        }

        return (array) $this->repository->readPaging($params, $filter, $select);
    }

    public function totalRows($params = []) {
	return (int) $this->repository->totalRows($params);
    }

    public function readAll($params = []): array

    {
        return (array) $this->repository->readAll($params);
    }

    public function find(array $params): object
    {
        return (object) $this->repository->find($params);
    }

    public function create(array $data)
    {
	if(!empty($data['title'])) $data['slug'] = $this->slug($data['title']);
	return (int) $this->repository->create($data);
    }

    public function update(int $id, array $data): bool
    {
	if(!empty($data['title'])) $data['slug'] = $this->slug($data['title']);
	return (bool) $this->repository->update($id, $data);
    }

    public function delete(int $id): bool
    {
	return (bool) $this->repository->delete($id);
    }

    public function slug($text): string {
	$slug = strtolower($text);
        $slug = trim(preg_replace('/[^A-Za-z0-9]+/', '-', $slug),'-');
        return $slug;
    }
}
