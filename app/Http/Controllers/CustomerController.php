<?php

namespace App\Http\Controllers;
use App\Models\Customer;
use Illuminate\Http\Request;

class CustomerController extends Controller
{

    public function index()
    {
        return view('Customers.index');
    }

    public  function getCustomers()
    {
        $data = Customer::query()->get();
        return response()->json(['data'=>$data]);

    }

    public function add(Request $request)
    {
        if($request->isMethod('post')){
            $customer = Customer::query()->make($request->all());
            if($customer->save()){
                $result = ['message'=>ucwords('the category has been saved!'),'result'=>ucwords('success')];
                return response()->json($result,200);
            }else{
                $result = ['message'=>ucwords('the category has been not saved!'),'result'=>ucwords('error')];
                return response()->json($result,404);
            } //end of if else condition
        }
    }

    public function edit($id = null, Request $request){
        $customer = Customer::query()->findOrFail($id);
        if($request->isMethod('post')){
            $customer->update($request->all());
            if($customer->save()){
                $result = ['message'=>ucwords('the category has been saved!'),'result'=>ucwords('success')];
                return response()->json($result,200);
            }else{
                $result = ['message'=>ucwords('the category has been not saved!'),'result'=>ucwords('error')];
                return response()->json($result,404);
            } //end of if else condition
        }
        return response()->json($customer);
    }

    public function delete($id = null){
        $customer = Customer::query()->findOrFail($id);
        if($customer->delete()){
            $result = ['message'=>ucwords('the category has been deleted!'),'result'=>ucwords('success')];
            return response()->json($result,200);
        }else{
            $result = ['message'=>ucwords('the category has been not deleted!'),'result'=>ucwords('error')];
            return response()->json($result,404);
        } //end of if else condition
    }



}
