<?php
namespace App\Repositories;

use App\WishList;

/**
 * Class WishListRepository
 * @package App\Repositories
 */
class WishListRepository extends RepositoryAbstract
{
    /**
     * @return string
     */
    public function model() : string
    {
        return WishList::class;
    }
}
