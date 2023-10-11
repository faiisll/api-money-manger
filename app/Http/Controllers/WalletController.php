<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Wallet;
use Illuminate\Support\Facades\Validator;
use App\Helpers\ResponseHelper;

class WalletController extends Controller
{
    public function index(){
        $wallets = Wallet::where('userId', auth()->id())->get()->toArray();

        return ResponseHelper::success($wallets, "Successfully get wallet.");

    }

    public function delete($id){
        $wallet = Wallet::where('id', $id)->where('userId', auth()->id())->first();

        // dd($category);
        if($wallet === null) return ResponseHelper::failedNoData();

        $wallet->delete();
        return ResponseHelper::success($wallet, "Data has been deleted.");


    }

    public function create(Request $request){
        $input = $request->all();
        
        $validator = Validator::make($input, [
            'name'      => 'required',
        ]);

        //if validation fails
        if ($validator->fails()) {
            $messages = $validator->messages();
            return ResponseHelper::failedValidation($messages->first());
        }

        //create user
        $wallet = Wallet::create([
            'name'  => $request->name,
            'balance'  => $request->balance,
            'userId' => auth()->id() 
        ]);

        //return response JSON user is created
        if($wallet) return ResponseHelper::success($wallet, "Data has been added.");


    }

    public function update(Request $req, $id){
        $input = $req->all();
        
        $validator = Validator::make($input, [
            'name' => 'min:1',
            'balance' => 'numeric'
        ]);

        //if validation fails
        if ($validator->fails()) {
            $messages = $validator->messages();
            return ResponseHelper::failedValidation($messages->first());
        }
        //get wallet by ID
        $wallet = Wallet::where('id', $id)->where('userId', auth()->id())->first();

        if($wallet === null) return ResponseHelper::failedNoData();


        $wallet->update([
            'name' =>  array_key_exists("name", $input) ? $req->name : $wallet->name,
            'balance' => array_key_exists("balance", $input) ? $req->balance : $wallet->balance,
        ]);

        if($wallet) return ResponseHelper::success($wallet, "Data has been updated.");


    }

    public function getSingleWallet($id){
        $wallet = Wallet::where('id', $id)->where('userId', auth()->id())->first();
        if($wallet === null) return ResponseHelper::failedNoData();

        if($wallet) return ResponseHelper::success($wallet);
    }
}
