<?php

namespace App\Http\Controllers;

use App\Http\Resources\CategoryResource;
use App\Http\Resources\ProductResource;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{

    use  ApiResponseTrait;

    public function index()
    {
        $categories  =CategoryResource::collection(Category::get());
        return $this->apiResponse($categories ,'ok',200);

    }



    public function store(Request $request)
    {
        $validator = Validator::make($request->all() , [
            "name"=>"required",
        ]);
        if ($validator->fails()){
            return $this->apiResponse(null,$validator ->errors() , 400);
        }
        $category =Category::create($request->all());
        if($category) {
            return $this->apiResponse(new CategoryResource($category), 'This Category save', 201);
        }
        return $this->apiResponse(null, 'This Category not save', 400);
    }




    public function show($id)
    {
        $category =  Category::find($id);
        if(  $category) {
            return $this->apiResponse(new CategoryResource($category), 'ok', 200);
        }
        return $this->apiResponse(null, 'This Category not found', 404);
    }





    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all() , [
            "name"=>"required",

        ]);
        if ($validator->fails()){
            return $this->apiResponse(null,$validator ->errors() , 400);
        }

        $category = Category::find($id);
        if(!  $category){
            return $this->apiResponse(null, 'This Category not found', 404);
        }

        $category->update($request->all());
        if(  $category) {
            return $this->apiResponse(new CategoryResource(  $category), 'This Category update', 201);
        }

    }





    public function destroy($id)
    {
        $category = Category::find($id);
        if(! $category){
            return $this->apiResponse(null, 'This Category not found', 404);
        }
        $category->delete($id);
        if( $category) {
            return $this->apiResponse(null, 'This Category deleted', 200);
        }


    }
}
