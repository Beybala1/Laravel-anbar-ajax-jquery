<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Client;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(request()->ajax()) {
            return datatables()->of(Brand::join('products','products.brand_id','=','brands.id')
            ->select('*','products.logo as products_logo','products.created_at as datetime','brands.id as brand_id','products.id')
            ->where('products.user_id','=',auth()->id())->get())
            ->addColumn('action',  function($row){
                $count = Order::where('product_id','=',$row->id)->count();

                if($count==0){
                    return
                    '<a href="javascript:void(0)" data-toggle="tooltip" title="Redaktə et" data-id="'.$row->id.'" data-original-title="Edit" class="edit btn btn-success pb-1 pt-1 edit edit-product">
                        <i class="bi bi-pencil-square"></i>
                    </a>
                    <a href="javascript:void(0);" id="delete-book" title="Sil" data-toggle="tooltip" data-original-title="Delete" data-id="'.$row->id.'" class="delete btn btn-danger pb-1 pt-1">
                        <i class="bi bi-x-lg"></i>
                    </a>';
                }
                else{
                    return  
                    '<a href="javascript:void(0)" data-toggle="tooltip" title="Redaktə et" data-id="'.$row->id.'" data-original-title="Edit" class="edit btn btn-success pb-1 pt-1 edit edit-product">
                        <i class="bi bi-pencil-square"></i>
                    </a>
                    <span class="d-inline-block" tabindex="0" data-toggle="tooltip" title="Bu brend aktifdir.">
                        <button disabled style="pointer-events: none;" class="btn btn-danger pb-1 pt-1"><i class="bi bi-x-lg"></i></button>
                    </span>'; 
                }
            })
            ->addColumn('created_at', function($row){
                /* $profit = 0;
                $total_profit = Product::where('products.user_id','=',auth()->id())->get();
                foreach ($total_profit as $p) {
                    $profit = (($p->sell - $p->buy)*$p->count)+$profit;
                }

                $orders_data=Order::join('products','products.id','=','orders.product_id')->where('products.user_id','=',auth()->id())->get();
                $profit_new = 0;
                foreach ($orders_data as $profit_now) {
                    if($profit_now->confirm==1){
                        $profit_new = (($profit_now->sell - $profit_now->buy)*$profit_now->order_count)+$profit_new;
                    }
                } */
                                   
                return date('d-m-Y H:i:s', strtotime($row->created_at) ).'
                <input type="hidden" id="brands" name="brands" value="'.Brand::where('user_id','=',auth()->id())->count().'">
                <input type="hidden" id="client" value="'.Client::where('user_id','=',auth()->id())->count().'">
                <input type="hidden" id="products" name="products" value="'.Product::where('user_id','=',auth()->id())->count().'">
                <input type="hidden" id="orders" value="'.Order::where('user_id','=',auth()->id())->count().'">
                ';
            })
            ->addIndexColumn()
            ->rawColumns(['created_at','action'])
            ->make(true);
        }
        return view('product',[
            'brand_list'=>Brand::where('user_id','=',auth()->id())->get(),
            'product_brand'=>Product::where('products.user_id','=',auth()->id())->get(),
            'orders_data'=>Order::join('products','products.id','=','orders.product_id')->where('products.user_id','=',auth()->id())->get(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {  

        $ProductId = $request->id;

        if($ProductId){
             
            $Product = Product::find($ProductId);

            $validator = Validator::make($request->all(),[
                'product'=>'required|min:3',
                'brand_id'=>'required',
                'buy'=>'required',
                'sell'=>'required',
                'count'=>'required',
                'logo' => 'image|mimes:jpeg,png,jpg,gif',
            ]);
  
            if($validator->fails()){
                return response()->json(['status'=>0, 'error'=>$validator->errors()->toArray()]);
            }
            else{
                
                date_default_timezone_set('Asia/Baku');

                 if($request->hasFile('logo')){

                    $path = '/home/filmbaxt/anbar.ml/'.$Product->logo.'';
                    File::delete($path);

                    $file = time().'.'.$request->logo->extension();
                    $request->logo->storeAS('uploads/images/',$file);
                    $Product -> logo = 'storage/app/uploads/images/'.$file;
                }   

                $Product->product = $request->product;
                $Product->brand_id = $request->brand_id;
                $Product->buy = $request->buy;
                $Product->sell = $request->sell;
                $Product->count = $request->count;
                $query = $Product->save();

                if( $query ){
                    return response()->json(['status'=>1, 'msg'=>'Məhsul uğurla yeniləndi']);
                }
            }
        }
        else{

            $validator = Validator::make($request->all(),[
                'product'=>'required|min:3',
                'brand_id'=>'required',
                'buy'=>'required',
                'sell'=>'required',
                'count'=>'required',
                'logo' => 'required|mimes:jpeg,png,jpg,gif',
            ]);
  
            if($validator->fails()){
                return response()->json(['status'=>0, 'error'=>$validator->errors()->toArray()]);
            }
            else{

                date_default_timezone_set('Asia/Baku');

                if($request->hasFile('logo')){

                    $file = time().'.'.$request->logo->extension();
                    $request->logo->storeAS('uploads/images/',$file);
                    $logo = 'storage/app/uploads/images/'.$file;
                }

                $values = [
                    'product'=> $request->product,
                    'brand_id'=> $request->brand_id,
                    'buy'=> $request->buy,
                    'sell'=> $request->sell,
                    'count'=> $request->count,
                    'logo' => $logo,
                    'user_id'=>auth()->id(),
                    'created_at'=>date('Y-m-d H:i:s'),
                ];

                $query = DB::table('products')->insert($values);

                if( $query ){
                    return response()->json(['status'=>1, 'msg'=>'Brend uğurla əlavə edildi']);
                }
            }
        }
    }
     
    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Product  $Product
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request)
    {   
        $where = array('id' => $request->id);
        $Brand  = Product::where($where)->first();
     
        return Response()->json($Brand);
    }
     
    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Product  $Product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $query = Product::find($request->id);
        $path = '/home/filmbaxt/anbar.ml/'.$query->logo.'';
        File::delete($path);
        $query->delete();
        if($query){
            return response()->json(['code'=>1, 'msg'=>'Məlumat  uğurla silindi']);
        }
    }
}