<?php

namespace App\Http\Controllers;

use App\Http\Resources\ProductResource;
use Illuminate\Auth\AuthenticationException;
use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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

        $input=$request->all();
        $validator = Validator::make($input , [
            'name'=>'required',
            'img_url'=>['nullable',],
            'date'=>'required',
            'description'=>'required',
            'quantity'=>'required',
            'price'=>'required',
            'category_id'=>'required',
        ]);
        $file_name=$this->saveImage($request->img_url,'images/product');

        $product = Product::query()->create([
            'name' => $request->name,
            'img_url' => $file_name,
            'date' => $request->date,
            'description' => $request->description,
            'quantity' => $request->quantity,
            'price' => $request->price,
            'category_id' => $request->category_id,
            'user_id' => auth()->id(),
           // 'views'=>0,

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
        $user =Auth::user();
        $input['user_id']=$user->id;
        $product =Product::create($input);
        if($product) {
            return $this->apiResponse(new ProductResource($product), 'This Product save', 201);
        }
        return $this->apiResponse(null, 'This Product not save', 400);
    }




    public function show($id)
    {
        $product =  Product::find($id);
        $product->increment('views');
        $discounts = $product->discounts()->get();
        $maxDiscount = null;
        foreach ($discounts as $discount){
            if (Carbon::parse($discount['date']) <= now()){
                $maxDiscount = $discount;}
        }
        if (!is_null($maxDiscount)){
            $discount_value =
                ($product->price*$maxDiscount['discount_percentage'])/100;
            $new_price = $product->price - $discount_value;
        }else{
            $new_price = $product->price;
        }
        $product->setAttribute('current_price', $new_price);
        if($product) {
            return $this->apiResponse(new ProductResource($product), 'ok', 200);
        }
        return $this->apiResponse(null, 'This Product not found', 404);
    }





    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'img_url' => 'required',
          ////  'date' => 'required',
            'description' => 'required',
            'quantity' => 'required',
            'price' => 'required',
            'category_id' => 'required',

        ]);
        if ($validator->fails()) {
            return $this->apiResponse(null, $validator->errors(), 400);
        }
        $product = Product::find($id);

        if (!$product) {
            return $this->apiResponse(null, 'This Product not found', 404);
        }
        if($product->user_id !=Auth::id()){
            return $this->apiResponse(null, 'you do not have rights', 400);
        }
        $product->update($request->all());
        if ($product) {
            return $this->apiResponse(new ProductResource($product), 'This Product update', 201);
        }
    }




    public function destroy($id)
    {
        $product =  Product::find($id);

        if(!$product){
            return $this->apiResponse(null, 'This Product not found', 404);
        }
        if($product->user_id !=Auth::id()){
            return $this->apiResponse(null, 'you do not have rights', 400);

        }
        $product->delete($id);
            return $this->apiResponse(null, 'This Product deleted', 200);

    }



// search method
    public function search($name)
    {
      $product=  Product::where("name","like","%".$name."%")->get();
        if (!$product) {
            return $this->apiResponse(null, 'This Product not found', 404);
        }
        return $this->apiResponse(ProductResource::collection($product), 'This Product you need', 201);

    }





    //sorting by Expiraton date
    public function sorting(){
        $products =Product::all()->sortBy('date');
        return $this-> apiResponse($products, 'sorting by Expiration date done', 201);
      //  return response()->json($products, 'sorting by Expiraton date done');
    }
}
