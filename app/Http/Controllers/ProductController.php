<?php

namespace App\Http\Controllers;

use App\Http\Resources\ProductResource;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
//use Auth;
class ProductController extends Controller
{

    use  ApiResponseTrait;

    public function index()
    {
        $products  =ProductResource::collection(Product::get());
        return $this->apiResponse($products,'ok',200);

    }



    public function store(Request $request)
    {
        $validator = Validator::make($request->all() , [
            'name'=>'required',
            'img_url'=>'required',
            'date'=>'required',
            'description'=>'required',
            'quantity'=>'required',
            'price'=>'required',
            'category_id'=>'required',
        ]);


        $product = Product::query()->create([
            'name' => $request->name,
            'price' => $request->price,
        ]);

        foreach ($request->list_discounts as $discount){
            $product->discounts()->create([
                'date' => $discount['date'],
                'discount_percentage' => $discount['discount_percentage'],
            ]);
        }


        if ($validator->fails()){
            return $this->apiResponse(null,$validator ->errors() , 400);
        }
        $product =Product::create($request->all());
        if($product) {
            return $this->apiResponse(new ProductResource($product), 'This Product save', 201);
        }
        return $this->apiResponse(null, 'This Product not save', 400);




    }




    public function show($id)
    {
        $product =  Product::find($id);


        $discounts = $product->discounts()->get();

        $maxDiscount = null;
        foreach ($discounts as $discount){
            if (Carbon::parse($discount['date']) <= now()){
                $maxDiscount = $discount;
            }
        }

        if (!is_null($maxDiscount)){
            $discount_value =
                ($product->price*$maxDiscount['discount_percentage'])/100;
            $product['current_price'] = $product->price - $discount_value;
        }
        if($product) {
            return $this->apiResponse(new ProductResource($product), 'ok', 200);
        }
        return $this->apiResponse(null, 'This Product not found', 404);




    }





    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all() , [
            'name'=>'required',
            'img_url'=>'required',
            'date'=>'required',
            'description'=>'required',
            'quantity'=>'required',
            'price'=>'required',
            'category_id'=>'required',

        ]);
        if ($validator->fails()){
            return $this->apiResponse(null,$validator ->errors() , 400);
        }

        $product =  Product::find($id);
        if(!$product){
            return $this->apiResponse(null, 'This Product not found', 404);
        }

            $product->update($request->all());
        if($product) {
            return $this->apiResponse(new ProductResource($product), 'This Product update', 201);
        }

    }





    public function destroy($id)
    {
        $product =  Product::find($id);
        if(!$product){
            return $this->apiResponse(null, 'This Product not found', 404);
        }
        $product->delete($id);
        if($product) {
            return $this->apiResponse(null, 'This Product deleted', 200);
        }


    }
}
