<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Buyer;
use App\Models\Seller;
use App\Models\Product;
use App\Models\Invoice;
use App\Models\Invoice_Item;
use Illuminate\Support\Facades\DB;
use Prgayman\Zatca\Facades\Zatca;
use Prgayman\Zatca\Utilis\QrCodeOptions; // Optional
class invoiceController extends Controller
{

    public function makeInvoice(){
        $products = Product::all(['id','name','price','product_code']);
        $seller= Seller::get()->first();
        $invoice=Invoice::orderBy('id', 'desc')
        ->pluck('id')
        ->first();
        $invoic_id=$invoice+1;
        // dd($products);
        return view('makeInvoice', compact('products','seller','invoic_id'));
    }
    public function storeInvoice(Request $request){
        $buyer=new Buyer();
        $buyer->name=Request('name');
        $buyer->building_no=Request('building_no');
        $buyer->street_name=Request('street_name');
        $buyer->city=Request('city');
        $buyer->country=Request('country');
        $buyer->postal_code=Request('postal_code');
        $buyer->additional_number=Request('additional_number');
        $buyer->vat_number=Request('buyer_vat_number');
        $buyer->other_buyer_id=Request('other_buyer_id');
        $buyer->district=Request('district');
        // $buyer->save();
        $buyer =Buyer::orderBy('id', 'desc')
                    ->pluck('id')
                    ->first();
                    //QR CODE VARIABLES
        $invoice_date=Request('invoice_issue_date');
        $seller_name=Request('seller_name');
        $vat_number=Request('vat_number');
        $total_amount_due=Request('total_amount_due');
        $total_vat=Request('total_vat');

            // INVOICE PART
        $invoice=new Invoice();
        $invoice->invoice_issue_date=$invoice_date;
        $invoice->date_of_supply=Request('date_of_supply');
        $invoice->branch=Request('branch');
        $invoice->salesman_name=Request('salesman_name');
        // $invoice->total_vat=10;
        $invoice->seller_id=Request('seller_id');
        $invoice->buyer_id=$buyer;
        // $invoice->save();

        $last = Invoice::orderBy('id', 'desc')->first();
        $last = $last->id;

        foreach ($request->product_id as $key => $product_id) {
        $invoice_item=new Invoice_Item();

            $quantity=$request->quantity[$key];
            $product_id=$request->product_id[$key];
            $tax=$request->tax[$key];
            $tax_amount=$request->tax_amount[$key];
        $invoice_item->quantity=$quantity;
        $invoice_item->total_vat=$tax_amount;
        $invoice_item->tax_percentage=$tax;
        $item_price_details = Product::where('id','=', $product_id)
        ->select('price')->first();
// dd($quantity);
        $item_price=$item_price_details->price;
        $total_price=$item_price*$quantity;

        $invoice_item->total_price=$total_price;
        $invoice_item->total_amount_due=$tax_amount+$total_price;

        $invoice_item->product_id=$product_id;
        $invoice_item->invoice_id=$last;
        // $invoice_item->save();
        }
        $price = DB::table('invoice__items')
        ->where('invoice_id', $last)
        ->sum('total_price');
        $vat = DB::table('invoice__items')
        ->where('invoice_id', $last)
        ->sum('total_vat');

    $invoice->total_price = $price;
    $invoice->total_vat = $vat;
    $invoice->total_amount_due = $vat+$price;
    // $invoice->save();

    $qrcode = zatca()
    ->sellerName($seller_name)
    ->vatRegistrationNumber($vat_number)
    ->timestamp($invoice_date)
    ->totalWithVat($total_amount_due)
    ->vatTotal($total_vat)
    ->toQrCode(
    );
        $request->session()->flash('status', ' successful!');

        return response()->json($qrcode);

        // return redirect()->back();
    }
    public  function qrcode(Request $request )
    {

        $qrcode = zatca()
        ->sellerName('Zatca')
        ->vatRegistrationNumber("123456789123456")
        ->timestamp("2021-12-01T14:00:09Z")
        ->totalWithVat('100.00')
        ->vatTotal('15.00')
        ->toQrCode(
        );
        return view('invoices.test',compact('qrcode'));
    }
    public  function priceTwo(Request $request )
    {
        $query= $request->get('productID');
        $op = Product::where('id','=', $query)

            ->pluck('price');

        $op=$op[0];
        return response()->json($op);
    }

    public function totalPriceUpdate(Request $request){
        $query1= $request->get('quantity');
        $query2= $request->get('productID');

        $op = Product::where('id','=', $query2)->pluck('price');


        $op=$op[0];
        $price=$query1*$op;
        return response()->json($price);
    }
    public function index(){
        $invoices = DB::table('invoices')
        ->join('buyers', 'invoices.buyer_id', '=', 'buyers.id')
        ->select('invoices.id','invoices.invoice_issue_date','invoices.total_amount_due', 'buyers.name')
        ->paginate(20);
        // ->all(['id','invoice_issue_date','total_amount_due'])->
        // $gallery= Gallery::all()->paginate(15);
        return view('invoices.invoiceList',compact('invoices'));
    }
    public function invoiceDetails($id){
        $invoice_items=Invoice_Item::with(['buyer','seller'])->where('shift_id', '=',$id)->get();

        return view('invoices.showInvoice',compact('invoice_items'));
      }
    //
}