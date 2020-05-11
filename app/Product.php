<?php

namespace App;

use App\Transformers\ProductTransformer;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{

    use SoftDeletes;
    protected $dates = ['deleted_at'];
    public $transformer = ProductTransformer::class;

    const AVALIABLE_PRODUCT = 'avaliable';
    const UNAVALIABLE_PRODUCT = 'unavaliable';

    //
    protected $fillable = [
        'name',
        'description',
        'qty',
        'status',
        'image',
        'seller_id',

    ];


    protected $hidden = [
        'pivot'
    ];
    
    public function isAvaliable()
    {

        return $this->status == Product::AVALIABLE_PRODUCT;
    }
    public function seller()
    {
        return $this->belongsTo(Seller::class);
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class);
    }
}
