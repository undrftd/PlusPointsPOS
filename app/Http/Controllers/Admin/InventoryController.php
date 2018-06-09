<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Product;
use Response;
use Validator;

use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;

class InventoryController extends Controller
{
    
    public function index()
    {
    	$products = Product::orderBy('product_name', 'asc')->paginate(7);
    	return view('admin.inventory')->with('products', $products);
    }

    public function create(Request $request)
    {
        $rules = array(
        'product_name' => 'required|unique:products,product_name',
        'product_desc' => 'required',
        'price' => 'required|numeric',
        'member_price' => 'required|numeric',
        'product_qty' => 'required|integer'	
        );

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails())
        {
            return Response::json(array('errors' => $validator->getMessageBag()->toArray()));
        }
        else
        {
            $product = new Product;
            $product->product_name = $request->product_name;
            $product->product_desc = $request->product_desc;
            $product->price = $request->price;
            $product->member_price = $request->member_price;
            $product->product_qty = $request->product_qty;
            $product->save();
        }	
    }

    public function edit(Request $request)
    {
    	$product = Product::find($request->product_id);

        $rules = array(
        'product_name' => "required|unique:products,product_name," . $product->product_id .",product_id",
        'product_desc' => 'required',
        'price' => 'required|numeric',
        'member_price' => 'required|numeric',
        'product_qty' => 'required|integer'	
        );

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails())
        {
            return Response::json(array('errors' => $validator->getMessageBag()->toArray()));
        }
        else
        {
            $product = Product::find($request->product_id);
            $product->product_name = $request->product_name;
            $product->product_desc = $request->product_desc;
            $product->price = $request->price;
            $product->member_price = $request->member_price;
            $product->product_qty = $request->product_qty;
            $product->save();
        }	
    }

    public function destroy(Request $request)
    {
        $product = Product::find($request->product_id)->delete();
    }

    public function search(Request $request)
    {
    	$search = $request->search;

        if($search == "")
        {
            return Redirect::to('inventory');
        }
        else
        {
            $products = Product::where(function($query) use ($request, $search)
                {
                    $query->where('product_name', 'LIKE', '%' . $search . '%')->orWhere('product_desc', 'LIKE', '%' . $search . '%');
                })->paginate(7);

            $products->appends($request->only('search'));
            $count = $products->count();
            $totalcount = Product::where(function($query) use ($request, $search)
                {
                    $query->where('product_name', 'LIKE', '%' . $search . '%')->orWhere('product_desc', 'LIKE', '%' . $search . '%');
                })->count();
            return view('admin.inventory')->with(['products' => $products, 'search' => $search, 'count' => $count, 'totalcount' => $totalcount]);  	
        }
    }

    public function lowstocks()
    {
        $products = Product::where('product_qty', '<=', 20)->orderBy('product_name', 'asc')->paginate(7);
        return view('admin.inventory')->with('products', $products);
    }

    public function healthystocks()
    {
        $products = Product::where('product_qty', '>', 20)->orderBy('product_name', 'asc')->paginate(7);
        return view('admin.inventory')->with('products', $products);
    }
}
