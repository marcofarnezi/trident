<?php
namespace Repositories;

use App\Repositories\RepositoryAbstract;
use App\Repositories\UserRepository;
use App\Repositories\WishListRepository;
use App\User;
use App\WishList;
use Illuminate\Database\Eloquent\Collection;
use Laravel\Lumen\Testing\DatabaseTransactions;

class WishListsRepositoryTest extends \TestCase
{
    use DatabaseTransactions;

    private $repository;
    private $userRepository;

    public function __construct($name = null, array $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName);
        $this->repository = new WishListRepository();
        $this->userRepository = new UserRepository();
    }

    public function test_instanceof_user_repository()
    {
        $this->assertInstanceOf(RepositoryAbstract::class, $this->repository);
    }

    public function test_model_return()
    {
        $this->assertEquals(WishList::class, $this->repository->model());
    }

    public function test_create_new_wish_list()
    {
        $user = $this->createUser();
        $date = [
            'title' => 'teste title',
            'user_id' => $user->id
        ];

        $result = $this->repository->create($date);

        $this->assertInstanceOf($this->repository->model(), $result);
        $this->assertEquals($date['title'], $result->title);
    }

    public function test_find_wish_list()
    {
        $user = $this->createUser();
        $date = [
            'title' => 'teste title',
            'user_id' => $user->id
        ];

        $result = $this->repository->create($date);

        $resultFound = $this->repository->find($result->id);

        $this->assertInstanceOf($this->repository->model(), $resultFound);
        $this->assertEquals($result->id, $resultFound->id);
    }

    public function test_update_wish_list()
    {
        $user = $this->createUser();
        $date = [
            'title' => 'teste title',
            'user_id' => $user->id
        ];

        $result = $this->repository->create($date);

        $dateUpdate['title'] = 'update title';

        $updated = $this->repository->update($dateUpdate, $result->id);
        $this->assertInstanceOf($this->repository->model(), $updated);

        $resultFound = $this->repository->find($result->id);
        $this->assertEquals($dateUpdate['title'], $resultFound->title);
    }

    public function test_get_all_wish_list()
    {
        $user = $this->createUser();
        $date = [
            'title' => 'teste title',
            'user_id' => $user->id
        ];

        $this->repository->create($date);

        $results = $this->repository->all();

        $this->assertInstanceOf(Collection::class, $results);
        $this->assertInstanceOf($this->repository->model(), $results->first());
    }

    public function test_delete_wish_list()
    {
        $resultsBefore = $this->repository->all();
        $user = $this->createUser();
        $date = [
            'title' => 'teste title',
            'user_id' => $user->id
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
        $this->assertInstanceOf(WishList::class, $this->repository->makeModel());
    }

    /**
     * @return User
     */
    private function createUser()
    {
        $date = [
            'name' => 'teste name',
            'email' => 'teste@teste.com',
            'password' => 'password'
        ];

        return $this->userRepository->create($date);
    }
}
