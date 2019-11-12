<?php
namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class Product
 * @package App
 */
class Product extends Model
{
    /**
     * The attributes that are mass assignable.
     * @var array
     */
    protected $fillable = ['id', 'wish_list_id', 'name', 'link'];

    /**
     * The attributes excluded from the model's JSON form.
     * @var array
     */
    protected $hidden = ['wish_list_id', 'created_at', 'updated_at'];

    /**
     * @return BelongsTo
     */
    public function wishList() : BelongsTo
    {
        return $this->belongsTo(WishList::class);
    }
}
