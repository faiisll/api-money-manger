<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use Illuminate\Support\Facades\Validator;

class CategoriesController extends Controller
{
    public function index(){

        $categories = Category::where('userId', auth()->id())->get()->toArray();
        $defaultCategory = Category::where('isDefault', 1)->get()->toArray();
        $merged = array_merge($defaultCategory, $categories);
        return response()->json($merged);
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
            return response()->json($validator->errors(), 422);
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
                'user'    => $category,  
            ], 201);
        }


    }
}
