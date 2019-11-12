<?php
namespace App\Exports;

use App\Contracts\ExportsInterface;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;

/**
 * Class ProductCounterExports
 * @package App\Exports]
 */
class ProductCounterExports implements ExportsInterface
{
    /**
     * @return array
     */
    public function headColuns() : array
    {
        return ['user', 'title', '#items'];
    }

    /**
     * @return Builder
     */
    public function getData() : Builder
    {
        return DB::table('users')
            ->select(
                'users.name',
                'wish_lists.title',
                DB::raw("count(products.id) as items")
            )
            ->join('wish_lists', 'users.id', '=', 'wish_lists.user_id')
            ->leftJoin('products', 'wish_lists.id', '=', 'products.wish_list_id')
            ->groupBy('users.name', 'wish_lists.title');
    }
}
