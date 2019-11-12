<?php
namespace App\Repositories;

use App\Contracts\RepositoryInterface;

/**
 * Class RepositoryAbstract
 * @package App\Repositories
 */
abstract class RepositoryAbstract implements RepositoryInterface
{
    protected $model;

    /**
     * RepositoryAbstract constructor.
     */
    public function __construct()
    {
        $this->makeModel();
    }

    /**
     * @param array $columns
     * @return mixed
     */
    public function all($columns = ['*'])
    {
        return $this->model->get($columns);
    }

    /**
     * @param array $data
     * @return mixed
     */
    public function create(array $data)
    {
        return $this->model->create($data);
    }

    /**
     * @param array $data
     * @param $id
     * @return mixed
     */
    public function update(array $data, $id)
    {
        $model = $this->find($id);
        $model->update($data);

        return $model;
    }

    /**
     * @param $id
     * @return mixed
     */
    public function delete($id)
    {
        $model = $this->find($id);
        return $model->delete();
    }

    /**
     * @param $id
     * @param array $columns
     * @return mixed
     */
    public function find($id, $columns = ['*'])
    {
        return $this->model->findOrFail($id, $columns);
    }

    /**
     * @param $field
     * @param $value
     * @param array $columns
     * @return mixed
     */
    public function findBy($field, $value, $columns = ['*'])
    {
        return $this->model->where($field, '=' ,$value)->get($columns);
    }

    /**
     * @return mixed
     */
    public function makeModel()
    {
        $class = $this->model();
        $this->model = new $class;

        return $this->model;
    }

    public abstract function model();
}
