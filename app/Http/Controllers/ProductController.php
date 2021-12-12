<?php

namespace App\Http\Controllers;

use App\Http\Resources\ProductResource;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

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
        //
    }


    public function show($id)
    {
        $product =  Product::find($id);
        if($product) {
            return $this->apiResponse(new ProductResource($product), 'ok', 200);
        }
        return $this->apiResponse(null, 'This Product not found', 404);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Product $product)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product)
    {
        //
    }
}
