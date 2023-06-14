<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class ProductsController extends Controller
{



    public function index(){
        $categories = Category::query()->get()->map(function ($row){
            return ['value'=>$row->category,'key'=>$row->id];
        })->pluck('value','key');
        return view('Products.index',compact('categories'));

    }

    public function getProducts(){
        $data = Product::query()->with('Categories')->get();
        return response()->json(['data'=>$data]);
    }

    public function add(Request $request){
        if($request->isMethod('post')){
            $product = Product::query()->make($request->all());

            if($request->hasFile('file')){
                $file = $request->file('file');

                $filename = uniqid().$file->getClientOriginalName();

                $path = public_path('img/product-img');
                if(!File::isDirectory($path)){
                    File::makeDirectory($path);
                }

                $filepath = public_path('img/product-img');

                if($file){
                    $file->move($filepath,$filename);
                }

                $product->product_image = 'product-img/'.$filename;
            }

            if($product->save()){
                $result = ['message'=>ucwords('the product has been saved!'),'result'=>ucwords('success')];
                return response()->json($result,200);
            }else{
                $result = ['message'=>ucwords('the product has been not saved!'),'result'=>ucwords('error')];
                return response()->json($result,404);
            }
        }
    }

    public function edit($id = null, Request $request){
        $product = Product::query()->findOrFail($id);
        if($request->isMethod('post')){
            $product->update($request->all());

            if($request->hasFile('file')){
                $file = $request->file('file');

                $filename = uniqid().$file->getClientOriginalName();

                $path = public_path('img/'.$product->product_image);
                if(File::isFile($path)){
                    File::delete($path);
                }

                $filepath = public_path('img/product-img');

                if($file){
                    $file->move($filepath,$filename);
                }

                $product->product_image = 'product-img/'.$filename;
            }

            if($product->save()){
                $result = ['message'=>ucwords('the product has been saved!'),'result'=>ucwords('success')];
                return response()->json($result,200);
            }else{
                $result = ['message'=>ucwords('the product has been not saved!'),'result'=>ucwords('error')];
                return response()->json($result,404);
            }
        }
        return response()->json($product);
    }

    public function delete($id = null){
        $product = Product::query()->findOrFail($id);
        $path = public_path('img/'.$product->product_image);
        if($product->delete()){
            if(File::isFile($path)){
                File::delete($path);
            }
            $result = ['message'=>ucwords('the product has been deleted!'),'result'=>ucwords('success')];
            return response()->json($result,200);
        }else{
            $result = ['message'=>ucwords('the product has been not deleted!'),'result'=>ucwords('error')];
            return response()->json($result,404);
        }
    }


}
