<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Buyer;
use App\Models\Seller;
use App\Models\Product;
use App\Models\Invoice;
use App\Models\Invoice_Item;
use Illuminate\Support\Facades\DB;

class mainController extends Controller
{
    public function home(){
        return view("index");
    }
    public function addSeller(){
        return view("settings");
    }
    public function storeSeller(Request $request){
        $request->validate([
//            'organization_name' => 'required|unique|max:255',
            // 'author.name' => 'required',
            // 'author.description' => 'required',
        ]);
        $seller=new Seller();
        $seller->organization_name=Request('organization_name');
        $seller->building_no=Request('building_no');
        $seller->street_name=Request('street_name');
        $seller->city=Request('city');
        $seller->country=Request('country');
        $seller->postal_code=Request('postal_code');
        $seller->additional_number=Request('additional_number');
        $seller->vat_number=Request('vat_number');
        $seller->other_seller_id=Request('other_seller_id');
        $seller->district=Request('district');
        $seller->save();
        $request->session()->flash('status', ' successful!');

        return redirect()->back();

    }
    public function addProduct(){
        return view("addProduct");
    }
    public function storeProduct(Request $request){
        $product=new Product();
        $product->name=Request('name');
        $product->name_in_arabic=Request('name_in_arabic');
        $product->product_code=Request('product_code');
        $product->price=Request('price');

        $product->save();
        $request->session()->flash('status', ' successful!');

        return redirect()->back();
    }
    public function index(){
        $products = DB::table('products')->paginate(20);
        // $gallery= Gallery::all()->paginate(15);
        return view('products.productsList',compact('products'));
    }

      //
}