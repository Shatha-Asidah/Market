<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Product;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;


class CommentController extends Controller
{
    use  ApiResponseTrait;


     //show all comment for one product
    public function index(Request $request, $id)
    {
        $product = Product::find($id);
        $comments = $product->comments;
        return response()->json($comments);
    }


    //add comment
    public function store(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'value' => ['required', 'string', 'min:1', 'max:400']
        ]);
        if ($validator->fails()) {
            return $this->apiResponse(null, $validator->errors(), 400);
        }

        $product = Product::find($id);
        if (!$product) {
            return $this->apiResponse(null, 'this Product not found', 404);
        }
        $comment = $product->comments()->create([
            'value' => $request->value,
            'product_id' => $id,
            'user_id' => Auth::id(),
        ]);
        return response()->json($comment);
    }



 // update one comment of one product
    public function update(Request $request, $id, $id2)
    {
        $validator = Validator::make($request->all(), [
            'value' => ['required', 'string', 'min:1', 'max:400']
        ]);
        if ($validator->fails()) {
            return $this->apiResponse(null, $validator->errors(), 400);
        }

        $product = Product::find($id);
        if (!$product) {
            return $this->apiResponse(null, 'this Product not found', 404);
        }
        $comment = Comment::find($id2);
        $comment->update([
            'value' => $request->value,
            'product_id' => $id,
            'user_id' => Auth::id(),
        ]);
       // return response()->json($comment, 'updated successfully');
        return $this->apiResponse($comment, 'updated successfully', 201);
    }





 //  delete one comment
    public function destroy($id, $id2)
    {
        $product = Product::find($id);
        if (!$product) {
            return $this->apiResponse(null, 'this Product not found', 404);
        }
        $comment = Comment::find($id2);
        if (!$comment) {
            return $this->apiResponse(null, 'This Comment not found', 404);
        }
        $comment->delete($id2);
        if ($comment) {
            return $this->apiResponse(null, 'This Comment deleted', 200);
        }
    }
}

