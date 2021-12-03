<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CartController extends Controller
{
    public function add_to_cart(Request $request)
    {
        $validation=Validator::make($request->all(),[
            'product_id'=>'required|numeric',
            'product_quantity'=>'required|numeric'
        ]);
        $product_id = $request->product_id;
        $product_qty = $request->product_qty;
        $product_size = $request->product_size;

        if (session()->has('cart')) {
            $cart = session()->get('cart');
        } else {
            $cart = [];
        }

        if (array_key_exists($product_id, $cart)) {
            $cart[$product_id]['qty'] += $product_qty;
        } else {
            $cart[$product_id] = [
                'qty' => $product_qty,
                'size' => $product_size,
            ];
        }

        session()->put('cart', $cart);
        return redirect()->back();
    }

}
