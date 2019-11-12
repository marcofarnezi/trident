<?php
namespace Repositories;

use App\Product;
use App\Repositories\ProductRepository;
use App\Repositories\RepositoryAbstract;
use App\Repositories\UserRepository;
use App\Repositories\WishListRepository;
use App\User;
use App\WishList;
use Illuminate\Database\Eloquent\Collection;
use Laravel\Lumen\Testing\DatabaseTransactions;

/**
 * Class ProductsRepositoryTest
 * @package Repositories
 */
class ProductsRepositoryTest extends \TestCase
{
    use DatabaseTransactions;

    private $repository;
    private $userRepository;
    private $wishListRepository;

    /**
     * ProductsRepositoryTest constructor.
     * @param null $name
     * @param array $data
     * @param string $dataName
     */
    public function __construct($name = null, array $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName);
        $this->repository = new ProductRepository();
        $this->userRepository = new UserRepository();
        $this->wishListRepository = new WishListRepository();
    }

    public function test_instanceof_product_repository()
    {
        $this->assertInstanceOf(RepositoryAbstract::class, $this->repository);
    }

    public function test_model_return()
    {
        $this->assertEquals(Product::class, $this->repository->model());
    }

    public function test_create_new_product()
    {
        $wishList = $this->createWishList();
        $date = [
            'name' => 'teste name',
            'link' => 'teste.link.com',
            'wish_list_id' => $wishList->id
        ];

        $result = $this->repository->create($date);

        $this->assertInstanceOf($this->repository->model(), $result);
        $this->assertEquals($date['name'], $result->name);
        $this->assertEquals($date['link'], $result->link);
    }

    public function test_find_product()
    {
        $wishList = $this->createWishList();
        $date = [
            'name' => 'teste name',
            'link' => 'teste.link.com',
            'wish_list_id' => $wishList->id
        ];

        $result = $this->repository->create($date);

        $resultFound = $this->repository->find($result->id);

        $this->assertInstanceOf($this->repository->model(), $resultFound);
        $this->assertEquals($result->id, $resultFound->id);
    }

    public function test_update_product()
    {
        $wishList = $this->createWishList();
        $date = [
            'name' => 'teste name',
            'link' => 'teste.link.com',
            'wish_list_id' => $wishList->id
        ];

        $result = $this->repository->create($date);

        $dateUpdate['name'] = 'update name';

        $updated = $this->repository->update($dateUpdate, $result->id);
        $this->assertInstanceOf($this->repository->model(), $updated);

        $resultFound = $this->repository->find($result->id);
        $this->assertEquals($dateUpdate['name'], $resultFound->name);
    }

    public function test_get_all_product()
    {
        $wishList = $this->createWishList();
        $date = [
            'name' => 'teste name',
            'link' => 'teste.link.com',
            'wish_list_id' => $wishList->id
        ];

        $this->repository->create($date);

        $results = $this->repository->all();

        $this->assertInstanceOf(Collection::class, $results);
        $this->assertInstanceOf($this->repository->model(), $results->first());
    }

    public function test_delete_product()
    {
        $resultsBefore = $this->repository->all();
        $wishList = $this->createWishList();
        $date = [
            'name' => 'teste name',
            'link' => 'teste.link.com',
            'wish_list_id' => $wishList->id
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
        $this->assertInstanceOf(Product::class, $this->repository->makeModel());
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

    /**
     * @return WishList
     */
    public function createWishList()
    {
        $user = $this->createUser();
        $date = [
            'title' => 'teste title',
            'user_id' => $user->id
        ];

        return $this->wishListRepository->create($date);
    }
}
