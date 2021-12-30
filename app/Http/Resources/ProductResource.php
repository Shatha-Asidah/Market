<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{

    public function toArray($request)
    {
        return  [
            "id"=>$this->id,
            "name"=>$this->name,
            "img_url"=>$this->img_url,
            "Expiraton date"=>$this->date,
            "description"=>$this->description,
            "quantity"=>$this->quantity,
           "price"=>$this->price,
            "category_id"=>$this->category_id,
            "views"=>$this->views,
            "user_id"=>$this->user_id,   ];
    }
}
