<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\Product;
use Barryvdh\DomPDF\PDF;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Response;

class SaleController extends Controller
{
    public function create(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'customer_id' => 'required|exists:customers,id',
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
            'payment_method' => 'required|string',
            'discount' => 'required|numeric',
            'taxes' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        $product = Product::find($request->input('product_id'));

        if (!$product) {
            return response()->json(['error' => 'Product not found'], 404);
        }

        $price = $product->price;
        $quantity = $request->input('quantity');
        $discount = $request->input('discount');
        $taxes = $request->input('taxes');
        $totalAmount = ($price * $quantity) - $discount + $taxes;

        $product->decrement('inventory', $quantity);

        $sale = new Sale();
        $sale->customer_id = $request->input('customer_id');
        $sale->product_id = $request->input('product_id');
        $sale->quantity = $quantity;
        $sale->payment_method = $request->input('payment_method');
        $sale->discount = $discount;
        $sale->taxes = $taxes;
        $sale->total_amount = $totalAmount;
        $sale->save();

        return response()->json(['sale' => $sale], 201);
    }

    public function generateInvoice($saleId)
    {
        $sale = Sale::find($saleId);

        if (!$sale) {
            return Response::json(['error' => 'Sale not found'], 404);
        }

        $pdf = app('dompdf.wrapper');
        $pdf->loadView('sale.invoice', compact('sale'));

        return $pdf->download('invoice.pdf');
    }
}
