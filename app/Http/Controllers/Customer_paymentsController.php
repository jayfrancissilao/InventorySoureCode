<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Customer_payment;
use Illuminate\Http\Request;

class Customer_paymentsController extends Controller
{

    public function index()
    {
        $customers = Customer::query()->get()->map(function ($row){
            return ['value'=>$row->first_name,'key'=>$row->id];
        })->pluck('value','key');
        return view('Payments.index',compact('customers'));
    }

    public function getPayment(){
        $data = Customer_payment::query()->with('Customers')->get();
        return response()->json(['data'=>$data]);
    }

    public function add(Request $request){
        if($request->isMethod('post')){
            $payments = Customer_payment::query()->make($request->all());
            if($payments->save()){
                $result = ['message'=>ucwords('the Customer Payments has been saved!'),'result'=>ucwords('success')];
                return response()->json($result,200);
            }else{
                $result = ['message'=>ucwords('the Customer Payments has been not saved!'),'result'=>ucwords('error')];
                return response()->json($result,404);
            } //end of if else condition
        }
    }

    public function edit($id = null, Request $request){
        $payments = Customer_payment::query()->findOrFail($id);
        if($request->isMethod('post')){
            $payments->update($request->all());


            if($payments->save()){
                $result = ['message'=>ucwords('the Customer Payments has been saved!'),'result'=>ucwords('success')];
                return response()->json($result,200);
            }else{
                $result = ['message'=>ucwords('the Customer Payments has been not saved!'),'result'=>ucwords('error')];
                return response()->json($result,404);
            }
        }
        return response()->json($payments);
    }

    public function delete($id = null){
        $addresses = Customer_payment::query()->findOrFail($id);
        if($addresses->delete()){
            $result = ['message'=>ucwords('the Customer Payments has been deleted!'),'result'=>ucwords('success')];
            return response()->json($result,200);
        }else{
            $result = ['message'=>ucwords('the Customer Payments has been not deleted!'),'result'=>ucwords('error')];
            return response()->json($result,404);
        } //end of if else condition
    }



}
