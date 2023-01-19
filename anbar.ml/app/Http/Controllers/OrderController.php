<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Order;
use App\Models\Client;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        
        if(request()->ajax()) {
            return datatables()->of(Order::join('clients','clients.id','=','orders.client_id')
            ->join('products','products.id','=','orders.product_id')
            ->join('brands','brands.id','=','products.brand_id')
            ->select('*','clients.id as client_id','products.id as product_id','brands.id as brand_id','orders.id',
            'orders.created_at',Client::raw("CONCAT(clients.name,' ',clients.lastname) as client"))
            ->where('orders.user_id','=',auth()->id())->get())
            ->addColumn('action',  function($row){
                if($row->confirm==0){
                    return '
                    <a href="javascript:void(0)" data-toggle="tooltip" title="Redaktə et" data-id="'.$row->id.'" data-original-title="Edit" class="edit btn btn-success pb-1 pt-1 edit edit-product">
                        <i class="bi bi-pencil-square"></i>
                    </a>

                    <a href="javascript:void(0);" id="delete-book" title="Sil" data-toggle="tooltip" data-original-title="Delete" data-id="'.$row->id.'" class="delete btn btn-danger pb-1 pt-1">
                        <i class="bi bi-x-lg"></i>
                    </a>

                    <a href="javascript:void(0);" id="confirm_order" data-toggle="tooltip" title="Təsdiq" data-original-title="Confirm" data-id="'.$row->id.'" class="confirm btn btn-primary pb-1 pt-1">
                        <i class="bi bi-check2"></i>
                    </a>';
                }
                else{
                    return
                    '<a href="javascript:void(0);" id="cancel_order" data-toggle="tooltip" title="Ləğv" data-original-title="Confirm" data-id="'.$row->id.'" class="cancel btn btn-warning pb-1 pt-1">
                        <i class="bi bi-x-lg"></i>
                    </a>';
                }
            })
            ->addColumn('created_at', function($row){
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
        return view('order',[
            'client_list'=>Client::where('user_id','=',auth()->id())->get(),
            'orders_data'=>Order::join('products','products.id','=','orders.product_id')->where('orders.user_id','=',auth()->id())->get(),
            'product_list'=>Product::join('brands','brands.id','=','products.brand_id')->select('*','products.id')->where('products.user_id','=',auth()->id())->get(),
            'product_brand'=>Product::where('products.user_id','=',auth()->id())->get(),
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

        $OrderId = $request->id;

        if($OrderId){
             
            $Order = Order::find($OrderId);

            $validator = Validator::make($request->all(),[
                'client_id'=>'required',
                'product_id'=>'required',
                'order_count'=>'required',
            ]);
  
            if($validator->fails()){
                return response()->json(['status'=>0, 'error'=>$validator->errors()->toArray()]);
            }
            else{
                
                date_default_timezone_set('Asia/Baku');

                $values = [
                    'client_id'=> $request->client_id,
                    'product_id'=> $request->product_id,
                    'order_count'=> $request->order_count,
                    'confirm'=> 0,
                    'updated_at'=>date('Y-m-d H:i:s'),
                ];
  
                $query = $Order->update($values);
                if( $query ){
                    return response()->json(['status'=>1, 'msg'=>'Sifariş uğurla yeniləndi']);
                }
            }
        }
        else{

            $validator = Validator::make($request->all(),[
                'client_id'=>'required',
                'product_id'=>'required',
                'order_count'=>'required',
            ]);
  
            if($validator->fails()){
                return response()->json(['status'=>0, 'error'=>$validator->errors()->toArray()]);
            }
            else{

                date_default_timezone_set('Asia/Baku');

                $values = [
                    'client_id'=> $request->client_id,
                    'product_id'=> $request->product_id,
                    'order_count'=> $request->order_count,
                    'confirm'=> 0,
                    'user_id'=>auth()->id(),
                    'created_at'=>date('Y-m-d H:i:s'),
                ];

                $query = DB::table('orders')->insert($values);

                if( $query ){
                    return response()->json(['status'=>1, 'msg'=>'Sifariş uğurla əlavə edildi']);
                }
            }
        }
    }
     
    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Order  $Order
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request)
    {   
        $where = array('id' => $request->id);
        $Brand  = Order::where($where)->first();
     
        return Response()->json($Brand);
    }
     
    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Order  $Order
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $query = Order::find($request->id)->delete();

        if($query){
            return response()->json(['code'=>1, 'msg'=>'Məlumat uğurla silindi']);
        }
    }

    public function confirm(Request $request)
    {
        $order = Order::find($request->id);
        $product = Product::find($order->product_id);
        
        $product_count = $product->count;
        $order_count = $order->order_count;
        $product_id = $order->product_id;

        if($order_count<=$product_count){

            $product_confirm = product::find($product_id);

            $result = $product_count - $order_count;

            $product_confirm->count = $result;

            $product_confirm->save();

            $order->confirm = 1;

            $order->save();

            return response()->json(['code'=>1, 'msg'=>'Məlumat uğurla təsdiqləndi']);
        }else{
            return response()->json(['code'=>0, 'msg'=>'Anbarda kifayət qədər məhsul yoxdur']);
        }
    }

    public function cancel(Request $request)
    {
        $order = Order::find($request->id);
        $product = Product::find($order->product_id);
        
        $product_count = $product->count;
        $order_count = $order->order_count;
        $product_id = $order->product_id;

        $product_confirm = product::find($product_id);
        $result = $product_count + $order_count;
        $product_confirm->count = $result;
        $product_confirm->save();            
        $order->confirm = 0;
        $order->save();
        return response()->json(['code'=>1, 'msg'=>'Sifariş ləğv edildi']);
    }
}