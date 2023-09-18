<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\Product;
use Barryvdh\DomPDF\PDF;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;

class SaleController extends Controller
{
    public function create(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'customer_id' => 'required|exists:customers,id',
            'products' => 'required|array|min:1',
            'products.*.product_id' => 'required|exists:products,id',
            'products.*.quantity' => 'required|integer|min:1',
            'payment_method' => 'required|string',
            'discount' => 'required|numeric',
            'taxes' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        $totalAmount = 0;

        foreach ($request->input('products') as $productData) {
            $product = Product::find($productData['product_id']);

            if (!$product) {
                return response()->json(['error' => 'Product not found'], 404);
            }

            $price = $product->price;
            $quantity = $productData['quantity'];
            $discount = $request->input('discount');
            $taxes = $request->input('taxes');
            $totalAmount += ($price * $quantity) - $discount + $taxes;
            $product->decrement('quantity', $quantity);
        }

        $sale = new Sale();
        $sale->customer_id = $request->input('customer_id');
        $sale->payment_method = $request->input('payment_method');
        $sale->discount = $request->input('discount');
        $sale->taxes = $request->input('taxes');
        $sale->total_amount = $totalAmount;
        $sale->save();

        foreach ($request->input('products') as $productData) {
            $product = Product::find($productData['product_id']);
            $quantity = $productData['quantity'];
            $subtotal = $product->price * $quantity;
            $sale->products()->attach($product->id, [
                'quantity' => $quantity,
                'subtotal' => $subtotal,
            ]);
        }
        return response()->json(['message' => 'Sale created successfully'], 201);
    }
    public function generateInvoice($id)
    {
        try {
            $sale = Sale::with(['customer', 'products'])->find($id);

            if (!$sale) {
                return response()->json(['error' => 'Sale not found'], 404);
            }

            $pdf = app('dompdf.wrapper');
            $pdf->loadView('sale.invoice', compact('sale'));
            $pdf->setPaper('A4');

            return $pdf->stream('invoice.pdf');
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function update(Request $request, $id)
    {

        $validator = Validator::make($request->all(), [
            'customer_id' => 'required|exists:customers,id',
            'products' => 'required|array|min:1',
            'payment_method' => 'required|string',
            'discount' => 'required|numeric',
            'taxes' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        $sale = Sale::find($id);

        if (!$sale) {
            return response()->json(['error' => 'Sale not found'], 404);
        }


        $sale->customer_id = $request->customer_id;
        $sale->payment_method = $request->payment_method;
        $sale->discount = $request->discount;
        $sale->taxes = $request->taxes;

        $sale->save();

        $productsData = $request->products;

        foreach ($productsData as $productData) {
            $product = Product::find($productData['product_id']);

            if (!$product) {
                return response()->json(['error' => 'Product not found'], 404);
            }

            $sale->products()->syncWithoutDetaching([
                $product->id => ['quantity' => $productData['quantity']]
            ]);
        }

        return response()->json(['message' => 'Sale updated successfully']);
    }

    public function destroy($saleId)
    {
        $sale = Sale::find($saleId);


        $sale->delete();

        return response()->json(['message' => 'Sale deleted successfully']);
    }
}
