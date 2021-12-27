<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Discount extends Model
{
    use HasFactory;
    protected $table = "discounts";

    protected $fillable = [
        'date','discount_percentage','product_id' ];


    protected $primaryKey = "id";

    public $timestamps=true ;



    public function product(){
        return $this->belongsTo(Category::class,'product_id');
    }

}
