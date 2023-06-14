<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Order_detail;
use Illuminate\Http\Request;

class Order_detailsController extends Controller
{
    public function index(){
        $customers = Customer::query()->get()->map(function ($row){
            return ['value'=>$row->first_name,'key'=>$row->id];
        })->pluck('value','key');
        return view('Order_details.index',compact('customers'));

    }

    public function getOrder_details(){
        $data = Order_detail::query()->with('Customers')->get();
        return response()->json(['data'=>$data]);
    }

    public function add(Request $request){
        if($request->isMethod('post')){
            $orders = Order_detail::query()->make($request->all());
            if($orders->save()){
                $result = ['message'=>ucwords('the category has been saved!'),'result'=>ucwords('success')];
                return response()->json($result,200);
            }else{
                $result = ['message'=>ucwords('the category has been not saved!'),'result'=>ucwords('error')];
                return response()->json($result,404);
            } //end of if else condition
        }
    }

    public function edit($id = null, Request $request){
        $orders = Order_detail::query()->findOrFail($id);
        if($request->isMethod('post')){
            $orders->update($request->all());


            if($orders->save()){
                $result = ['message'=>ucwords('the product has been saved!'),'result'=>ucwords('success')];
                return response()->json($result,200);
            }else{
                $result = ['message'=>ucwords('the product has been not saved!'),'result'=>ucwords('error')];
                return response()->json($result,404);
            }
        }
        return response()->json($orders);
    }

    public function delete($id = null){
        $orders = Order_detail::query()->findOrFail($id);
        if($orders->delete()){
            $result = ['message'=>ucwords('the category has been deleted!'),'result'=>ucwords('success')];
            return response()->json($result,200);
        }else{
            $result = ['message'=>ucwords('the category has been not deleted!'),'result'=>ucwords('error')];
            return response()->json($result,404);
        } //end of if else condition
    }
}
