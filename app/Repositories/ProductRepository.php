<?php
namespace App\Repositories;

use App\Product;

/**
 * Class ProductRepository
 * @package App\Repositories
 */
class ProductRepository extends RepositoryAbstract
{
    /**
     * @return string
     */
    public function model() : string
    {
        return Product::class;
    }
}
