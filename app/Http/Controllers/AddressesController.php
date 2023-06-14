<?php

namespace App\Http\Controllers;

use App\Models\Address;
use App\Models\Customer;
use Illuminate\Http\Request;


class AddressesController extends Controller
{


    public function index(){
        $customers = Customer::query()->get()->map(function ($row){
            return ['value'=>$row->first_name,'key'=>$row->id];
        })->pluck('value','key');
        return view('Addresses.index',compact('customers'));

    }


    public function getAddresses(){
        $data = Address::query()->with('Customers')->get();
        return response()->json(['data'=>$data]);
    }

    public function add(Request $request){
        if($request->isMethod('post')){
            $addressses = Address::query()->make($request->all());
            if($addressses->save()){
                $result = ['message'=>ucwords('the Address has been saved!'),'result'=>ucwords('success')];
                return response()->json($result,200);
            }else{
                $result = ['message'=>ucwords('the Address has been not saved!'),'result'=>ucwords('error')];
                return response()->json($result,404);
            } //end of if else condition
        }
    }

    public function edit($id = null, Request $request){
        $addresses = Address::query()->findOrFail($id);
        if($request->isMethod('post')){
            $addresses->update($request->all());


            if($addresses->save()){
                $result = ['message'=>ucwords('the Address has been saved!'),'result'=>ucwords('success')];
                return response()->json($result,200);
            }else{
                $result = ['message'=>ucwords('the Address has been not saved!'),'result'=>ucwords('error')];
                return response()->json($result,404);
            }
        }
        return response()->json($addresses);
    }

    public function delete($id = null){
        $addresses = Address::query()->findOrFail($id);
        if($addresses->delete()){
            $result = ['message'=>ucwords('the Address has been deleted!'),'result'=>ucwords('success')];
            return response()->json($result,200);
        }else{
            $result = ['message'=>ucwords('the Address has been not deleted!'),'result'=>ucwords('error')];
            return response()->json($result,404);
        } //end of if else condition
    }



}
