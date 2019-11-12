<?php
namespace App\Repositories;

use App\User;

/**
 * Class UserRepository
 * @package App\Repositories
 */
class UserRepository extends RepositoryAbstract
{
    /**
     * @return string
     */
    public function model() : string
    {
        return User::class;
    }
}
