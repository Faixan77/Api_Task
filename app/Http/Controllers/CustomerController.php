<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CustomerController extends Controller
{
    public function create(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'contact_info' => 'required|string',
            'address' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        $customer = new Customer();
        $customer->name = $request->input('name');
        $customer->contact_info = $request->input('contact_info');
        $customer->address = $request->input('address');
        $customer->save();

        return response()->json(['customer' => $customer], 201);
    }

}
