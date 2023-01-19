<?php

namespace App\Http\Controllers;

use App\Models\Xerc;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class XercController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(request()->ajax()) {
            return datatables()->of(Xerc::select('*')->where('user_id','=',auth()->id()))
            ->addColumn('action', 'book-action')
            ->addColumn('created_at', function($row){
                return date('d-m-Y H:i:s', strtotime($row->created_at) );
            })
            ->addIndexColumn()
            ->make(true);
        }
        return view('xerc',[
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

        $XercId = $request->id;

        if($XercId){
             
            $Xerc = Xerc::find($XercId);

            $validator = Validator::make($request->all(),[
                'xerc_product'=>'required|min:2',
                'count'=>'required',
            ]);
  
            if($validator->fails()){
                return response()->json(['status'=>0, 'error'=>$validator->errors()->toArray()]);
            }
            else{
                date_default_timezone_set('Asia/Baku');
                $values = [
                     'xerc_product'=>$request->xerc_product,
                     'count'=>$request->count,
                     'updated_at'=>date('Y-m-d H:i:s'),
                ];
  
                $query = $Xerc->update($values);
                if( $query ){
                    return response()->json(['status'=>1, 'msg'=>'Xərc uğurla yeniləndi']);
                }
            }
        }
        else{

            $validator = Validator::make($request->all(),[
                'xerc_product'=>'required|min:2',
                'count'=>'required',
            ]);
  
            if($validator->fails()){
                return response()->json(['status'=>0, 'error'=>$validator->errors()->toArray()]);
            }
            else{
                date_default_timezone_set('Asia/Baku');
                $values = [
                    'xerc_product'=>$request->xerc_product,
                    'count'=>$request->count,
                    'user_id'=>auth()->id(),
                    'created_at'=>date('Y-m-d H:i:s'),
                ];
  
                $query = DB::table('xercs')->insert($values);
                if( $query ){
                    return response()->json(['status'=>1, 'msg'=>'Xərc uğurla əlavə edildi']);
                }
            }

            /*$Xerc = new Xerc;
            date_default_timezone_set('Asia/Baku');
            $Xerc->xerc_product = $request->xerc_product;
            $Xerc->count = $request->count;
            $Xerc->save();
         
            return Response()->json($Xerc);*/
        }
    }
     
     
    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Xerc  $Xerc
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request)
    {   
        $where = array('id' => $request->id);
        $Xerc  = Xerc::where($where)->first();
     
        return Response()->json($Xerc);
    }
     
     
    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Xerc  $Xerc
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $id = $request->id;
        $query = Xerc::find($id)->delete();

        if($query){
            return response()->json(['code'=>1, 'msg'=>'Məlumat  uğurla silindi']);
        }
    }
}