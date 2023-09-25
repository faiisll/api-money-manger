<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Models\Category;
use App\Models\Wallet;

class RegisterController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'      => 'required',
            'email'     => 'required|email|unique:users',
            'password'  => 'required|min:6'
        ]);

         //if validation fails
         if ($validator->fails()) {
            return response()->json($validator->messages(), 422);
        }

        //create user
        $user = User::create([
            'name'      => $request->name,
            'email'     => $request->email,
            'password'  => bcrypt($request->password)
        ]);

        //return response JSON user is created
        if($user) {
            $category = Category::create([
                'name'  => "Food & Drinks",
                'isDefault' => true,
                'icon'  => "default",
                'type'  => 0,
                'userId' =>$user->id 
            ]);
            $wallet = Wallet::create([
                'name'  => "Cash",
                'isDefault' => true,
                'balance' => 0,
                'userId' =>$user->id 
            ]);
            return response()->json([
                'success' => true,
                'user'    => $user,  
            ], 201);
        }

        //return JSON process insert failed 
        return response()->json([
            'success' => false,
        ], 409);
    }
}
