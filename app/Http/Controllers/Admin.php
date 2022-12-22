<?php
namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use Termwind\Components\Span;

class Admin extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(request()->ajax()) {
            return datatables()->of(User::select('*')->latest())
            ->addColumn('action',  function($row){
                if($row->id==auth()->id()){
                    return '';
                }elseif($row->status==0){
                    return
                    '<a href="javascript:void(0);" id="block" title="Blok et" data-toggle="tooltip" data-original-title="Blok" data-id="'.$row->id.'" class="block btn btn-warning pb-1 pt-1">
                        <i class="bi bi-slash-circle"></i>
                    </a>';
                }
                else{
                    return
                    '<a href="javascript:void(0);" id="open" title="Blokdan çıxart" data-toggle="tooltip" data-id="'.$row->id.'" class="unblock btn btn-primary pb-1 pt-1">
                        <i class="bi bi-slash-circle"></i>
                    </a>
                    <a href="javascript:void(0);" id="delete-book" title="Sil" data-toggle="tooltip" data-original-title="Delete" data-id="'.$row->id.'" class="delete btn btn-danger pb-1 pt-1">
                        <i class="bi bi-x-lg"></i>
                    </a>';
                }
            })
            ->addColumn('created_at', function($row){
                return date('d-m-Y H:i:s', strtotime($row->created_at) );
            })
            ->addColumn('status', function($row){
                if(auth()->id()===$row->id){
                    //return 'online';
                    return '<span class="text-success">Çevrimçi</span>';

                }else{
                    return '<span class="text-danger">Oflayn</span>';
                }
            })
            ->rawColumns(['action', 'status'])
            ->addIndexColumn()
            ->make(true);
        }
        return view('auth.admin',[
            'product_brand'=>Product::where('products.user_id','=',auth()->id())->get(),
            'orders_data'=>Order::join('products','products.id','=','orders.product_id')->where('orders.user_id','=',auth()->id())->get(),
        ]);
    }

    public function block(Request $request)
    { 
        $user = User::find($request->id);
        $user->status = 1;
        $user->save();
        return response()->json(['code'=>1, 'msg'=>'İstifadəçi blok edildi']);
    }

    public function unblock(Request $request)
    { 
        $user = User::find($request->id);
        $user->status = 0;
        $user->save();
        return response()->json(['code'=>1, 'msg'=>'İstifadəçi blokdan çıxarıldı']);
    }

    public function destroy(Request $request)
    {
        $id = $request->id;
        $query = User::find($id)->delete();

        if($query){
            return response()->json(['code'=>1, 'msg'=>'Məlumat  uğurla silindi']);
        }
    }
}
