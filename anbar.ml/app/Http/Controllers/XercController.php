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
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
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
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $where = array('id' => $id);
        $Xerc  = Xerc::where($where)->first();
     
        return Response()->json($Xerc);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $query = Xerc::find($id)->delete();

        if($query){
            return response()->json(['code'=>1, 'msg'=>'Məlumat  uğurla silindi']);
        }
    }
}
