<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Wallet;
use Illuminate\Support\Facades\Validator;

class WalletController extends Controller
{
    public function index(){
        $wallets = Wallet::where('userId', auth()->id())->get()->toArray();
        return response()->json([
            "data" =>$wallets,
            "message" => "Successfully get categories."
        ], 200);

    }

    public function delete($id){
        $category = Wallet::where('id', $id)->where('userId', auth()->id())->first();

        // dd($category);
        if($category === null){
            
            return response()->json([
                "status" => false,
                "message" => "Delete failed, data not found."
    
            ], 404);
        }else{
            $category->delete();
            return response()->json([
                "status" => true,
                "message" => "Data has been deleted."
    
            ], 200);

        }


    }

    public function create(Request $request){
        $input = $request->all();
        
        $validator = Validator::make($input, [
            'name'      => 'required',
        ]);

        //if validation fails
        if ($validator->fails()) {
            return response()->json($validator->messages(), 422);
        }

        //create user
        $category = Wallet::create([
            'name'  => $request->name,
            'balance'  => $request->balance,
            'userId' => auth()->id() 
        ]);

        //return response JSON user is created
        if($category) {
            return response()->json([
                'success' => true,
                'data'    => $category,  
            ], 201);
        }


    }

    public function getSingleWallet($id){
        $wallet = Wallet::where('id', $id)->first();

        if($wallet){
            return $wallet;

        }else{
            return false;
        }

    }
}
