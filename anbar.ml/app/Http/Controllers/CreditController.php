<?php

namespace App\Http\Controllers;

use App\Models\Credit;
use App\Models\Order;
use App\Models\Client;
use App\Models\Product;
use App\Models\CreditView;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class CreditController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(request()->ajax()) {
            return datatables()->of(Credit::join('clients','clients.id','=','credits.credit_client_id')
            ->join('products','products.id','=','credits.credit_product_id')
            ->join('brands','brands.id','=','products.brand_id')
            ->select('*','credits.id as credit_id','clients.id as client_id','products.id as product_id','brands.id as brand_id',
            'credits.created_at as datetime',Client::raw("CONCAT(clients.name,' ',clients.lastname) as client"))
            ->orderby('credits.id','desc')->where('credits.user_id','=',auth()->id())->get())
            ->addColumn('action',  function($row){
                $credit = Credit::find($row->credit_id);
                if($credit->credit_confirm!=$credit->time && $credit->credit_cancel==0){
                    return'
                    <a href="/pay/'.$row->credit_id.'" id="pay" data-toggle="tooltip" title="Ödəniş" data-original-title="Pay" data-id="'.$row->credit_id.'" class="pay btn btn-primary pb-1 pt-1">
                        <i class="bi bi-check2"></i>
                    </a>
                    <a href="javascript:void(0)" data-toggle="tooltip" title="Redaktə et" data-id="'.$row->credit_id.'" data-original-title="Edit" class="edit btn btn-success pb-1 pt-1 edit edit-product">
                        <i class="bi bi-pencil-square"></i>
                    </a>
                    <a href="javascript:void(0)" data-toggle="tooltip" title="Ləğv et" data-id="'.$row->credit_id.'" data-original-title="cancel" class="cancel btn btn-danger pb-1 pt-1">
                        <i class="bi bi-x-lg"></i>
                    </a>
                    <a href="/pay_check/'.$row->credit_id.'" title="Ödəniş tarixçəsi" class="btn btn-warning pb-1 pt-1">
                        <i class="bi bi-clipboard"></i>
                    </a>';
                }
                else{
                    return'';
                }
            })
            ->addColumn('sell',  function($row){
                $product = Credit::join('products','products.id','=','credits.credit_product_id')->where('credits.user_id','=',auth()->id())->find($row->credit_id);
                $profit = $product->sell * $product->credit_count;
                return $profit;
            })
            ->addColumn('total_debt',  function($row){
                $profit = 0;
                $product = Credit::join('products','products.id','=','credits.credit_product_id')->where('credits.user_id','=',auth()->id())->find($row->credit_id);
                $profit = ($product->sell * $product->credit_count)+$product->sell * $product->credit_count*$product->percent/100;
                return $profit;
            })
            ->addColumn('last_debt',  function($row){
                $product = Credit::join('products','products.id','=','credits.credit_product_id')->where('credits.user_id','=',auth()->id())->find($row->credit_id);
                if($product->credit_confirm==0){
                    $profit = 0;
                    $profit = ($product->sell * $product->credit_count)+$product->sell * $product->credit_count*$product->percent/100-$product->first_payment;
                    return $profit;
                }
                elseif($product->credit_cancel==1){
                    $profit = 0;
                    return $profit;
                }
                else{
                    $month = 0;
                    $profit = 0;
                    $month = (($product->sell * $product->credit_count)+$product->sell * $product->credit_count*$product->percent/100-$product->first_payment)/$product->time;
                    $profit = ($product->sell * $product->credit_count)+$product->sell * $product->credit_count*$product->percent/100-$product->first_payment-$month*$product->credit_confirm;
                    return mb_substr($profit,0,6);
                }
            })
            ->addColumn('monthly',  function($row){
                $profit = 0;
                $product = Credit::join('products','products.id','=','credits.credit_product_id')->where('credits.user_id','=',auth()->id())->find($row->credit_id);
                $profit = (($product->sell * $product->credit_count)+$product->sell * $product->credit_count*$product->percent/100-$product->first_payment)/$product->time;
                return mb_substr($profit,0,5);

            })
            ->addColumn('depozit',  function($row){
                $depozit = Credit::join('products','products.id','=','credits.credit_product_id')->where('credits.user_id','=',auth()->id())->find($row->credit_id);
                return mb_substr($depozit->depozit,0,5);
            })
            ->addColumn('month_payed',  function($row){
                $product = Credit::join('products','products.id','=','credits.credit_product_id')->where('credits.user_id','=',auth()->id())->find($row->credit_id);
                if($product->credit_confirm==$product->time){
                    return 'Ödənildi';
                }elseif($product->credit_cancel==1){
                    return 'Ləğv edildi';
                }else{
                    return $product->time.'/'.$product->credit_confirm.'';
                }
            })
            ->addColumn('created_at', function($row){
                return date('d-m-Y H:i:s', strtotime($row->created_at) );
            })
            ->addIndexColumn()
            ->make(true);
        }
   
        return view('credit',[
            'client_list'=>Client::where('user_id','=',auth()->id())->get(),
            'orders_data'=>Order::join('products','products.id','=','orders.product_id')->where('orders.user_id','=',auth()->id())->get(),
            'product_list'=>Product::join('brands','brands.id','=','products.brand_id')->where('products.user_id','=',auth()->id())->get(),
            'product_brand'=>Product::where('products.user_id','=',auth()->id())->get(),
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
        $CreditId = $request->id;

        if($CreditId){
             
            $Credit = Credit::find($CreditId);

            $validator = Validator::make($request->all(),[
                'credit_client_id'=>'required',
                'credit_product_id'=>'required',
                'time'=>'required',
                'percent'=>'required',
                'credit_count'=>'required',
                'first_payment'=>'required',
            ]);
  
            if($validator->fails()){
                return response()->json(['status'=>0, 'error'=>$validator->errors()->toArray()]);
            }
            else{
                
                date_default_timezone_set('Asia/Baku');

                $values = [
                    'credit_client_id'=> $request->credit_client_id,
                    'credit_product_id'=> $request->credit_product_id,
                    'time'=> $request->time,
                    'percent'=> $request->percent,
                    'credit_count'=> $request->credit_count,
                    'first_payment'=> $request->first_payment,
                    'user_id'=>auth()->id(),
                    'updated_at'=>date('Y-m-d H:i:s'),
                ];
  
                $query = $Credit->update($values);
                if( $query ){
                    return response()->json(['status'=>1, 'msg'=>'Kredit uğurla yeniləndi']);
                }
            }
        }
        else{

            $validator = Validator::make($request->all(),[
                'credit_client_id'=>'required',
                'credit_product_id'=>'required',
                'time'=>'required',
                'percent'=>'required',
                'credit_count'=>'required',
                'first_payment'=>'required',
            ]);
  
            if($validator->fails()){
                return response()->json(['status'=>0, 'error'=>$validator->errors()->toArray()]);
            }
            else{

                date_default_timezone_set('Asia/Baku');

                $values = [
                    'credit_client_id'=> $request->credit_client_id,
                    'credit_product_id'=> $request->credit_product_id,
                    'time'=> $request->time,
                    'percent'=> $request->percent,
                    'depozit'=>0,
                    'credit_confirm'=>0,
                    'credit_cancel'=>0,
                    'credit_code'=> microtime(true),
                    'credit_count'=> $request->credit_count,
                    'first_payment'=> $request->first_payment,
                    'user_id'=>auth()->id(),
                    'created_at'=>date('Y-m-d H:i:s'),
                ];

                $query = DB::table('credits')->insert($values);

                if( $query ){
                    return response()->json(['status'=>1, 'msg'=>'Kredit uğurla əlavə edildi']);
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
        $Credit  = Credit::where($where)->first();
     
        return Response()->json($Credit);
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
        $query = Credit::find($id)->delete();

        if($query){
            return response()->json(['code'=>1, 'msg'=>'Məlumat uğurla silindi']);
        }
    }

    public function pay($id){
        $monthly = 0;
        $product = Credit::join('products','products.id','=','credits.credit_product_id')->where('credits.user_id','=',auth()->id())->find($id);
        $monthly = (($product->sell * $product->credit_count)+$product->sell * $product->credit_count*$product->percent/100-$product->first_payment)/$product->time;
        return view('pay',[
            'credit'=>Credit::join('products','products.id','=','credits.credit_product_id')
            ->join('brands','brands.id','=','products.brand_id')
            ->join('clients','clients.id','=','credits.credit_client_id')->select('*','credits.id as credit_id')
            ->find($id),
            'monthly'=>$monthly,
            'orders_data'=>Order::join('products','products.id','=','orders.product_id')->where('orders.user_id','=',auth()->id())->get(),
            'product_brand'=>Product::where('products.user_id','=',auth()->id())->get(),
        ]);
    }

    public function pay_confirm(Request $request, $id){
        $monthly = 0;
        $product = Credit::join('products','products.id','=','credits.credit_product_id')->select('*','credits.id')->where('credits.user_id','=',auth()->id())->find($id);
        $monthly = (($product->sell * $product->credit_count)+$product->sell * $product->credit_count*$product->percent/100-$product->first_payment)/$product->time;
        if($monthly<=$request->price){
            date_default_timezone_set('Asia/Baku');
            for($i=0; $i<=$product->credit_confirm; $i++){}
            $product->credit_confirm = $i;
            $product->depozit = ($request->price - mb_substr($monthly,0,5))+$product->depozit ;
            $product->save();
            
            $credit_view= new CreditView();
            $credit_view->credit_id = $product->id;
            $credit_view->client_id = $product->credit_client_id;
            $credit_view->client_product = $product->credit_product_id;
            $credit_view->client_payed = $request->price;
            $credit_view->user_id = auth()->id();
            $credit_view->save();
            return redirect('/credit')->with('message','Əməliyyat uğurla həyata keçirildi');
        }
        elseif($request->price<=$monthly && $monthly<=$product->depozit){
            date_default_timezone_set('Asia/Baku');
            for($i=0; $i<=$product->credit_confirm; $i++){}
            $product->credit_confirm = $i;
            $product->depozit = $product->depozit - (mb_substr($monthly,0,5) - $request->price);
            $product->save();
            
            $credit_view = new CreditView();
            $credit_view->credit_id = $product->id;
            $credit_view->client_id = $product->credit_client_id;
            $credit_view->client_product = $product->credit_product_id;
            $credit_view->client_payed = $request->price;
            $credit_view->user_id = auth()->id();
            $credit_view->save();
            return redirect('/credit')->with('message','Əməliyyat uğurla həyata keçirildi');
        }
        elseif($monthly<=$request->price+$product->depozit){
            date_default_timezone_set('Asia/Baku');
            for($i=0; $i<=$product->credit_confirm; $i++){}
            $product->credit_confirm = $i;
            $product->depozit = ($request->price + $product->depozit) - mb_substr($monthly,0,5); 
            $product->save();
            
            $credit_view = new CreditView();
            $credit_view->credit_id = $product->id;
            $credit_view->client_id = $product->credit_client_id;
            $credit_view->client_product = $product->credit_product_id;
            $credit_view->client_payed = $request->price;
            $credit_view->user_id = auth()->id();
            $credit_view->save();
            return redirect('/credit')->with('message','Əməliyyat uğurla həyata keçirildi');
        }
        return back()->with('message_fail','Daxil etdiyiniz məbləğ aylıq ödəniş məbləğindən azdır');
    }

    public function cancel(Request $request){
        $credit = Credit::where('credits.user_id','=',auth()->id())->find($request->id);
        $credit->credit_cancel = 1;
        $credit->save();
        return response()->json(['code'=>1, 'msg'=>'Qaliq borc ləğv edildi']);
    }

    public function pay_check($id)
    {
        if(request()->ajax()) {
            return datatables()->of(CreditView::join('credits','credits.id','=','credit_views.credit_id')
            ->join('products','products.id','=','credits.credit_product_id')
            ->join('brands','brands.id','=','products.brand_id')
            ->join('clients','clients.id','=','credits.credit_client_id')->select('*','credits.id','credit_views.created_at',Client::raw("CONCAT(clients.name,' ',clients.lastname) as client"))
            ->where('credit_views.user_id','=',auth()->id())->where('credits.id','=',$id)->orderby('credit_views.id','desc')->get())
            ->addColumn('created_at', function($row){
                return date('d-m-Y H:i:s', strtotime($row->created_at) );
            })
            ->addIndexColumn()
            ->make(true);
        } 
        return view('payCheck', [
            'credit_id'=>Credit::find($id),
            'orders_data'=>Order::join('products','products.id','=','orders.product_id')->where('orders.user_id','=',auth()->id())->get(),
            'product_brand'=>Product::where('products.user_id','=',auth()->id())->get(),
        ]);
    }
}
