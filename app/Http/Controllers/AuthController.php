<?php

namespace App\Http\Controllers;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Validation\Rule;
use Symfony\Component\HttpFoundation\Response;

class AuthController extends Controller
{
    use  ApiResponseTrait;

    public function register(Request $request)
    {
        $validator = Validator::make($request->all() , [
            'name'=>['required','string','max:255'],
            'email'=>['required','string','email','max:255'],
            'password'=>['required','string','min:8'],
            'whatsapp_url'=>'required',
            'facebook_url'=>'required',

        ]);
        if ($validator->fails()){
            return $this->apiResponse(null,$validator ->errors() , 400);
        }

        $input= $request->all();
        $input['password']= Hash::make($input['password']);
        $user=User::create($input);

        $tokenResult= $user->createToken('Personal Access Token');
        $data["user"]=$user;
        $data["token_type"]= 'Bearer';
        $data["access_token"]=$tokenResult->accessToken;

        return $this->apiResponse($data,'user register successfully' , 200);

    }


    /**
     * @throws AuthenticationException
     */
    public function login(Request $request)
    {
        $validator = Validator::make($request->all() , [
            'email'=>['required','string','email','max:255'],
            'password'=>['required','string','min:8'],


        ]);
        if ($validator->fails()){
            return $this->apiResponse(null,$validator ->errors() , 400);
        }

        $credentials = request(['email','password']);
        if(!Auth::attempt($credentials)){
            throw new AuthenticationException();
        }

        $user= $request->user();

        //add token to user
        $tokenResult= $user->createToken('Personal Access Token');
        $data["user"]=$user;
        $data["token_type"]= 'Bearer';
        $data["access_token"]=$tokenResult->accessToken;

        return $this->apiResponse($data,'user register successfully' , 200);

    }
}
//fvm;ldslv;sdep

