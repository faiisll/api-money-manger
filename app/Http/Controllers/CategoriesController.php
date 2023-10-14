<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseHelper;
use Illuminate\Http\Request;
use App\Models\Category;
use Illuminate\Support\Facades\Validator;

class CategoriesController extends Controller
{
    public function index(){
        $categories = Category::where('userId', auth()->id())->paginate(1000);
        return ResponseHelper::success($categories, "Successfully get categories.");
    }

    public function test(){
        $res = [
            'message' => "hello this is test"
        ];
        return response()->json($res, 200);
    }

    public function delete($id){
        $category = Category::where('id', $id)->where('userId', auth()->id())->first();
        if($category === null) return ResponseHelper::failedNoData();
        if($category->isDefault) return ResponseHelper::failedValidation("Failed, cannot delete default category");

        $category->delete();
        return ResponseHelper::success($category, "Successfully, data has been deleted.");
    }

    public function create(Request $request){
        $input = $request->all();
        
        $validator = Validator::make($input, [
            'name' => 'required',
            'icon'  => 'string',
            'variant'  => 'string',
            'type'  => 'boolean',
        ]);

        //if validation fails
        if ($validator->fails()) {
            $messages = $validator->messages();
            return ResponseHelper::failedValidation($messages->first());
        }

        //create user
        $category = Category::create([
            'name'  => $request->name,
            'icon'  => $request->icon ? $request->icon : 'default',
            'type'  => $request->type ? $request->type : 0,
            'variant' => $request->variant ? $request->variant : 'primary',
            'userId' => auth()->id() 
        ]);

        //return response JSON user is created
        if($category) {
            return ResponseHelper::success($category, "Data has been added.");
        }


    }

    public function update(Request $req, $id){
        $category = Category::where('id', $id)->where('userId', auth()->id())->first();
        $input = $req->all();

        // dd($category);
        if($category === null) return ResponseHelper::failedNoData();
        
        $validator = Validator::make($input, [
            'name'  => 'string|min:3',
            'icon'  => 'string',
            'variant'  => 'string',
            'type'  => 'boolean',
        ]);

        //if validation fails
        if ($validator->fails()) {
            $messages = $validator->messages();
            return ResponseHelper::failedValidation($messages->first());
        }

        $category->update([
            'name' => array_key_exists('name', $input) ? $req->name : $category->name,
            'icon' => array_key_exists('icon', $input) ? $req->icon : $category->icon,
            'type' => array_key_exists('type', $input) ? $req->type : $category->type,
            'variant' => array_key_exists('variant', $input) ? $req->variant : $category->variant,

        ]);
        
        return ResponseHelper::success($category, "Data has been updated.");

    }

    public function getSingleCategory($id){
        $category = Category::where('id', $id)->where('userId', auth()->id())->first();

        if($category === null) return ResponseHelper::failedNoData();
        return ResponseHelper::success($category);

    }
}
