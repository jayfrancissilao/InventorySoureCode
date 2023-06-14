<?php

namespace App\Http\Controllers;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoriesController extends Controller
{

    public function index(){
        return view('Categories.index');
    }

    public function getCategories(){
        $data = Category::query()->get();
        return response()->json(['data'=>$data]);
    }

    public function add(Request $request){
        if($request->isMethod('post')){
            $category = Category::query()->make($request->all());
            if($category->save()){
                $result = ['message'=>ucwords('the category has been saved!'),'result'=>ucwords('success')];
                return response()->json($result,200);
            }else{
                $result = ['message'=>ucwords('the category has been not saved!'),'result'=>ucwords('error')];
                return response()->json($result,404);
            } //end of if else condition
        }
    }

    public function edit($id = null, Request $request){
        $category = Category::query()->findOrFail($id);
        if($request->isMethod('post')){
            $category->update($request->all());
            if($category->save()){
                $result = ['message'=>ucwords('the category has been saved!'),'result'=>ucwords('success')];
                return response()->json($result,200);
            }else{
                $result = ['message'=>ucwords('the category has been not saved!'),'result'=>ucwords('error')];
                return response()->json($result,404);
            } //end of if else condition
        }
        return response()->json($category);
    }

    public function delete($id = null){
        $category = Category::query()->findOrFail($id);
        if($category->delete()){
            $result = ['message'=>ucwords('the category has been deleted!'),'result'=>ucwords('success')];
            return response()->json($result,200);
        }else{
            $result = ['message'=>ucwords('the category has been not deleted!'),'result'=>ucwords('error')];
            return response()->json($result,404);
        } //end of if else condition
    }

}
