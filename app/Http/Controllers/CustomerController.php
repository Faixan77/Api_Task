<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CustomerController extends Controller
{
    public function create(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'contact_info' => 'required|string',
            'address' => 'required|string',
        ]);

        $customer = Customer::create([
            'name' => $request->input('name'),
            'contact_info' => $request->input('contact_info'),
            'address' => $request->input('address'),
        ]);

        return response()->json(['customer' => $customer], 201);
    }
}
