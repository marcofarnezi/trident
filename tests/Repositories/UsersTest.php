<?php
namespace Repositories;

use App\Repositories\RepositoryAbstract;
use App\Repositories\UserRepository;
use App\User;
use Illuminate\Database\Eloquent\Collection;
use Laravel\Lumen\Testing\DatabaseTransactions;

/**
 * Class UsersRepositoryTest
 * @package Repositories
 */
class UsersRepositoryTest extends \TestCase
{
    use DatabaseTransactions;

    private $repository;

    /**
     * UsersRepositoryTest constructor.
     * @param null $name
     * @param array $data
     * @param string $dataName
     */
    public function __construct($name = null, array $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName);
        $this->repository = new UserRepository();
    }

    public function test_instanceof_user_repository()
    {
        $this->assertInstanceOf(RepositoryAbstract::class, $this->repository);
    }

    public function test_model_return()
    {
        $this->assertEquals(User::class, $this->repository->model());
    }

    public function test_create_new_user()
    {
        $date = [
            'name' => 'teste name',
            'email' => 'teste@teste.com',
            'password' => 'password'
        ];

        $result = $this->repository->create($date);

        $this->assertInstanceOf($this->repository->model(), $result);
        $this->assertEquals($date['name'], $result->name);
        $this->assertEquals($date['email'], $result->email);
        $this->assertEquals($date['password'], $result->password);
    }

    public function test_find_user()
    {
        $date = [
            'name' => 'teste name',
            'email' => 'teste@teste.com',
            'password' => 'password'
        ];

        $result = $this->repository->create($date);

        $resultFound = $this->repository->find($result->id);

        $this->assertInstanceOf($this->repository->model(), $resultFound);
        $this->assertEquals($result->id, $resultFound->id);
    }

    public function test_update_user()
    {
        $date = [
            'name' => 'teste name',
            'email' => 'teste@teste.com',
            'password' => 'password'
        ];

        $result = $this->repository->create($date);

        $dateUpdate['name'] = 'update name';

        $updated = $this->repository->update($dateUpdate, $result->id);
        $this->assertInstanceOf($this->repository->model(), $updated);

        $resultFound = $this->repository->find($result->id);

        $this->assertEquals($dateUpdate['name'], $resultFound->name);
    }

    public function test_get_all_user()
    {
        $date = [
            'name' => 'teste name',
            'email' => 'teste@teste.com',
            'password' => 'password'
        ];

        $this->repository->create($date);

        $results = $this->repository->all();

        $this->assertInstanceOf(Collection::class, $results);
        $this->assertInstanceOf($this->repository->model(), $results->first());
    }

    public function test_delete_user()
    {
        $resultsBefore = $this->repository->all();
        $date = [
            'name' => 'teste name',
            'email' => 'teste@teste.com',
            'password' => 'password'
        ];

        $result = $this->repository->create($date);
        $resultsAfter = $this->repository->all();
        $count = count($resultsAfter) - 1;
        $this->assertEquals(count($resultsBefore), $count);

        $this->repository->delete($result->id);
        $resultsAfter = $this->repository->all();
        $count = count($resultsAfter);
        $this->assertEquals(count($resultsBefore), $count);
    }

    public function test_make_model()
    {
        $this->assertInstanceOf(User::class, $this->repository->makeModel());
    }
}
