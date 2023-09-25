<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use Illuminate\Support\Facades\Validator;

class CategoriesController extends Controller
{
    public function index(){

        $categories = Category::where('userId', auth()->id())->get()->toArray();
        return response()->json([
            "data" =>$categories,
            "message" => "Successfully get categories."
        ], 200);
    }

    public function test(){
        $res = [
            'message' => "hello this is test"
        ];

        return response()->json($res, 200);
    }

    public function delete($id){
        $category = Category::where('id', $id)->where('userId', auth()->id())->first();

        // dd($category);
        if($category === null){
            
            return response()->json([
                "status" => false,
                "message" => "Delete failed, data not found."
    
            ], 400);
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
            'icon'     => 'required',
            'type'  => 'required',
        ]);

        //if validation fails
        if ($validator->fails()) {
            return response()->json($validator->messages(), 422);
        }

        //create user
        $category = Category::create([
            'name'  => $request->name,
            'icon'  => $request->icon,
            'type'  => $request->type,
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

    public function getSingleCategory($id){
        $category = Category::where('id', $id)->first();

        if($category){
            return $category;

        }else{
            return false;
        }

    }
}
