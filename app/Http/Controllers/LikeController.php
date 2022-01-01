<?php

namespace App\Http\Controllers;

use App\Models\Like;
use App\Models\Product;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class LikeController extends Controller
{

    // show all likes
    public function index(Request $request ,$id)
    {      $product = Product::find($id);
        $likes = $product->likes()->get();
        return response()->json($likes);
    }

    public function store(Request $request ,$id)

    {
        $product = Product::find($id);
        if($product->likes()->where('user_id',Auth::id())->exists())
        {
            $product->likes()->where('user_id',Auth::id())->delete();
        }
        else
        {
            $product->likes()->create(['user_id'=>Auth::id()]);
        }

        return response()->json(null);
    }
}
