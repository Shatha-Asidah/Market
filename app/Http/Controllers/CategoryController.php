<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{

    use  ApiResponseTrait;

    public function index()
    {
        $categories  = Category::get();

        return $this->apiResponse($categories,'ok',200);

    }

//     public function store(Request $request)
//     {
//         //
//     }


     public function show($id)
     {
        $category = Category::find($id);
        if($category) {
            return $this->apiResponse($category, 'ok', 200);
        }
         return $this->apiResponse(null, 'This Category not found', 401);
     }

//     /**
//      * Update the specified resource in storage.
//      *
//      * @param  \Illuminate\Http\Request  $request
//      * @param  \App\Models\Category  $category
//      * @return \Illuminate\Http\Response
//      */
//     public function update(Request $request, Category $category)
//     {
//         //
//     }

//     /**
//      * Remove the specified resource from storage.
//      *
//      * @param  \App\Models\Category  $category
//      * @return \Illuminate\Http\Response
//      */
//     public function destroy(Category $category)
//     {
//         //
//     }
 }
