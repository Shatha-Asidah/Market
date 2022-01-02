<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    protected $table = "products";

    protected $fillable = [
      'name','img_url','date','description','quantity','price','category_id','user_id','views'];



    protected $primaryKey = "id";

    public $timestamps=true ;


    public function category(){
        return $this->belongsTo(Category::class,'category_id');
    }


    public function user(){
        return $this->belongsTo(User::class,'user_id');
    }

    public function discounts()
    {
        return $this->hasMany(Discount::class,'product_id')->orderBy('date');
    }
    public function comments(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Comment::class,'product_id');}

    public function likes(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Like::class,'product_id');
    }

      public $withCount = ['comments' , 'likes'];
}
