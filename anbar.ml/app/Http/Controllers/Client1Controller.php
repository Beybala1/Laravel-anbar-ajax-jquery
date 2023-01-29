<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Client;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ClientController extends Controller
{
    public function index()
    {
        if(request()->ajax()) {
            return datatables()->of(Client::select('*')->where('user_id','=',auth()->id()))
            ->addColumn('action', function($row){
                $count = Order::where('client_id','=',$row->id)->count();

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
        return view('client',[
            'data_list'=>Client::where('user_id','=',auth()->id())->get(),
            'product_brand'=>Product::where('products.user_id','=',auth()->id())->get(),
            'orders_data'=>Order::join('products','products.id','=','orders.product_id')->where('products.user_id','=',auth()->id())->get(),
        ]);

        $client_count = Client::where('user_id','=',auth()->id())->count();

        return response()->json([
            'count' => $client_count
        ]);
    }

    public function store(Request $request)
    {  

        $ClientId = $request->id;

        if($ClientId){
             
            $Client = Client::find($ClientId);

            $validator = Validator::make($request->all(),[
                'name' => 'required|min:2',
                'lastname' => 'required|min:2',
                'email' => 'required|email',
                'phone' => 'required|min:8',
            ]);
  
            if($validator->fails()){
                return response()->json(['status'=>0, 'error'=>$validator->errors()->toArray()]);
            }
            else{
                
                date_default_timezone_set('Asia/Baku');
               
                $values = [
                    'name'=>$request->name,
                    'lastname'=>$request->lastname,
                    'email'=>$request->email,
                    'phone'=>$request->phone,
                    'updated_at'=>date('Y-m-d H:i:s'),
                ];
  
                $query = $Client->update($values);

                if( $query ){
                    return response()->json(['status'=>1, 'msg'=>'Müştəri uğurla yeniləndi']);
                }
            }
        }
        else{

            $validator = Validator::make($request->all(),[
                'name' => 'required|min:2',
                'lastname' => 'required|min:2',
                'email' => 'required|min:2|unique:clients,email,NULL,id,user_id,'.Auth::id().'',
                'phone' => 'required|min:2|unique:clients,phone,NULL,id,user_id,'.Auth::id().'',
            ]);
  
            if($validator->fails()){
                return response()->json(['status'=>0, 'error'=>$validator->errors()->toArray()]);
            }
            else{

                date_default_timezone_set('Asia/Baku');

                $values = [
                    'name'=>$request->name,
                    'lastname'=>$request->lastname,
                    'email'=>$request->email,
                    'phone'=>$request->phone,
                    'user_id'=>auth()->id(),
                    'created_at'=>date('Y-m-d H:i:s'),
                ];

                $query = DB::table('clients')->insert($values);

                if( $query ){
                    return response()->json(['status'=>1, 'msg'=>'Müştəri uğurla əlavə edildi']);
                }
            }
        }
    }
     
    public function edit(Request $request)
    {   
        $where = array('id' => $request->id);
        $Client  = Client::where($where)->first();
     
        return Response()->json($Client);

    }
     
    public function destroy(Request $request)
    {
        $id = $request->id;
        $query = Client::find($id)->delete();
        
        if($query){
            return response()->json(['code'=>1, 'msg'=>'Məlumat uğurla silindi']);
        }
    }
}