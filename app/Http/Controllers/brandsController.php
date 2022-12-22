<?php

namespace App\Http\Controllers;

use Datatables;
use App\Models\brands;
use App\Models\orders;
use App\Models\clients;
use App\Models\products;s
use Illuminate\Http\Request;
use App\Exports\brandsExport;
use App\Imports\brandsImport;
use App\Http\Requests\brandsRequest;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Storage;


class brandsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(request()->ajax()) {
            return datatables()->of(brands::select('*')->where('user_id','=',Auth::id()))
            ->addColumn('action', 'book-action')
            ->addColumn('image', function($row){
                return $row->foto;
            })
            ->addColumn('created_at', function($row){
                return date('d-m-Y h:i:s', strtotime($row->created_at) );
            })
            ->addIndexColumn()
            ->make(true);
        }
        return view('brands',[

            //Toplam qazancin statistikasi
            'products_data'=>products::orderby('id','desc')
            ->join('brands','brands.id','=','products.brands_id')
            ->select('products.id','products.ad as mehsul','products.miqdar','products.alis',
            'products.satis','brands.ad as brend')
            ->where('products.user_id','=',Auth::id())
            ->get(),
            
            //Cari qazanc
            'orders_data'=>orders::join('clients','clients.id','=','orders.clients_id')
            ->join('products','products.id','=','orders.product_id')
            ->join('brands','brands.id','=','products.brands_id')
            ->select('orders.id','orders.miqdar as order_miqdar','orders.created_at','orders.tesdiq',
            'products.ad as mehsul','products.miqdar','products.foto','products.alis',
            'products.satis','brands.ad as brend','clients.ad as client','clients.soyad')
            ->where('orders.user_id','=',Auth::id())
            ->orderby('id','desc')
            ->get(),

            'total_client'=>clients::where('user_id','=',Auth::id())->count(),
            'total_brand'=>brands::where('user_id','=',Auth::id())->count(),
            'total_product'=>products::where('user_id','=',Auth::id())->count(),
            'total_order'=>orders::where('user_id','=',Auth::id())->count(),
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
        $bookId = $request->id;

        $con = new brands();
        
            if($bookId){ 

                $say = $con->where('ad','=',$request->ad)->where('id','!=',$bookId)->count();
                
                $book = brands::find($bookId);

                if($request->hasFile('foto')){
                    $file = time().'.'.$request->foto->extension();
                    $request->foto->storeAS('uploads/brands/',$file);
                    $book ->foto = 'storage/app/uploads/brands/'.$file;
                }   
            }
            else{

                $say = $con->where('ad','=',$request->ad)->where('user_id','=',Auth::id())->count();
                $book = new brands;

                $file = time().'.'.$request->foto->extension();
                $request->foto->storeAS('uploads/brands/',$file);
                $book ->foto = 'storage/app/uploads/brands/'.$file;
            }
        if($say == 0){

            date_default_timezone_set('Asia/Baku');
            $book->ad = $request->ad;
            $book->user_id = Auth::id();
            $book->save();
            
            return Response()->json($book);
        }
        return redirect()->back('brands');
    }
     
    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\book  $book
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request)
    {   
        $where = array('id' => $request->id);
        $book  = brands::where($where)->first();
     
        return Response()->json($book);
    }
     
    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\book  $book
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        
        $con2 = products::where('brands_id','=',$request->id)->count();

        if($con2==0){

            $book = brands::where('id',$request->id)->delete();
            return Response()->json($book);
        
        }
        return redirect()->back('brands');
    }









    /*
    public function brands_ins(brandsRequest $post){
         
        date_default_timezone_set('Asia/Baku');
        $con = new brands();

        $say = $con->where('ad','=',$post->ad)->count();

        if($say == 0){

            $post->validate([
                'foto' => 'required|image|mimes:jpg,png,jpeg,gif,svg|max:2048',
            ]);

            if($post->file('foto')){
                $file = time().'.'.$post->foto->extension();
                $post->foto->storeAS('public/uploads/brands/',$file);
                $con -> foto = 'storage/uploads/brands/'.$file;
            }
            else{
                $con->foto = '';
            }
            
            $con->ad = $post->ad;
            $con->user_id = Auth::id();
           
            $con->save();

            return redirect()->route('brands')->with('success','Brend uğurla daxil edildi');
        }
        return redirect()->route('brands')->with('fail','Bu brend mövcuddur');
    }
    
    public function brands_list(){
        return view('brands',[
            'brands_data'=>brands::orderby('id','desc')->where('user_id','=',Auth::id())->get(),

            //Toplam qazancin statistikasi
            'products_data'=>products::orderby('id','desc')
            ->join('brands','brands.id','=','products.brands_id')
            ->select('products.id','products.ad as mehsul','products.miqdar','products.alis',
            'products.satis','brands.ad as brend')
            ->where('products.user_id','=',Auth::id())
            ->get(),

            //Cari qazanc
            'orders_data'=>orders::join('clients','clients.id','=','orders.clients_id')
            ->join('products','products.id','=','orders.product_id')
            ->join('brands','brands.id','=','products.brands_id')
            ->select('orders.id','orders.miqdar as order_miqdar','orders.created_at','orders.tesdiq',
            'products.ad as mehsul','products.miqdar','products.foto','products.alis',
            'products.satis','brands.ad as brend','clients.ad as client','clients.soyad')
            ->where('orders.user_id','=',Auth::id())
            ->orderby('id','desc')
            ->get(),

            'total_client'=>clients::where('user_id','=',Auth::id())->count(),
            'total_brand'=>brands::where('user_id','=',Auth::id())->count(),
            'total_product'=>products::where('user_id','=',Auth::id())->count(),
            'total_order'=>orders::where('user_id','=',Auth::id())->count(),
        ]);
    }
    
    //Silme zamani he ve yox
    public function brands_delete($id){
        return view('brands',[

            'brands_data'=>brands::orderby('id','desc')->where('user_id','=',Auth::id())->get(),

            'sildata'=>brands::find($id),

            //Toplam qazancin statistikasi
            'products_data'=>products::orderby('id','desc')
            ->join('brands','brands.id','=','products.brands_id')
            ->select('products.id','products.ad as mehsul','products.miqdar','products.alis',
            'products.satis','brands.ad as brend')
            ->where('products.user_id','=',Auth::id())
            ->get(),

            //Cari qazanc
            'orders_data'=>orders::join('clients','clients.id','=','orders.clients_id')
            ->join('products','products.id','=','orders.product_id')
            ->join('brands','brands.id','=','products.brands_id')
            ->select('orders.id','orders.miqdar as order_miqdar','orders.created_at','orders.tesdiq',
            'products.ad as mehsul','products.miqdar','products.foto','products.alis',
            'products.satis','brands.ad as brend','clients.ad as client','clients.soyad')
            ->where('orders.user_id','=',Auth::id())
            ->orderby('id','desc')
            ->get(),

            'total_client'=>clients::where('user_id','=',Auth::id())->count(),
            'total_brand'=>brands::where('user_id','=',Auth::id())->count(),
            'total_product'=>products::where('user_id','=',Auth::id())->count(),
            'total_order'=>orders::where('user_id','=',Auth::id())->count(),
        ]);
    }
    
    //Silme emeliyyati
    public function brands_sil($id){
        //brands::join('products','products.brands','=','brands.id')->joim('brands.id','=',$id->id)->find($id)->delete();
        brands::find($id)->delete();
        return redirect()->route('brands')->with('success','Brend uğurla silindi');
    }
    
    //Edit zamani forma gelsin
    public function brands_edit($id){
        return view('brands',[
            'brands_data'=>brands::orderby('id','desc')->where('user_id','=',Auth::id())->get(),
            'editdata'=>brands::find($id),

            //Toplam qazancin statistikasi
            'products_data'=>products::orderby('id','desc')
            ->join('brands','brands.id','=','products.brands_id')
            ->select('products.id','products.ad as mehsul','products.miqdar','products.alis',
            'products.satis','brands.ad as brend')
            ->where('products.user_id','=',Auth::id())
            ->get(),

            //Cari qazanc
            'orders_data'=>orders::join('clients','clients.id','=','orders.clients_id')
            ->join('products','products.id','=','orders.product_id')
            ->join('brands','brands.id','=','products.brands_id')
            ->select('orders.id','orders.miqdar as order_miqdar','orders.created_at','orders.tesdiq',
            'products.ad as mehsul','products.miqdar','products.foto','products.alis',
            'products.satis','brands.ad as brend','clients.ad as client','clients.soyad')
            ->orderby('id','desc')
            ->where('orders.user_id','=',Auth::id())
            ->get(),

            'total_client'=>clients::where('user_id','=',Auth::id())->count(),
            'total_brand'=>brands::where('user_id','=',Auth::id())->count(),
            'total_product'=>products::where('user_id','=',Auth::id())->count(),
            'total_order'=>orders::where('user_id','=',Auth::id())->count(),
        ]);
    }      

    //Update
    public function brands_update(brandsRequest $post){

        $con = new brands();

        $say = $con->where('ad','=',$post->ad)->count();

        $edit = brands::find($post->id);
        
        
        if($post->file('foto')){

            $post->validate([
                'foto' => 'required|image|mimes:jpg,png,jpeg,gif,svg|max:2048',
            ]);

            $file = time().'.'.$post->foto->extension();
            $post->foto->storeAS('public/uploads/brands/',$file);
            $edit ->foto = 'storage/uploads/brands/'.$file;
        } 
        else{
            $edit -> foto = $post->carifoto;
        }

        if($say == 0){

            $edit->ad = $post->ad;
            $edit->save();

            return redirect()->route('brands')->with('success','Brend uğurla yeniləndi');
        }
        return redirect()->route('brands')->with('fail','Bu brend mövcuddur');
    }

    public function export(){
        return Excel::download(new brandsExport,'brendler.xlsx');
    }

    public function import(){
        Excel::download(new brandsImport,request()->file('file'));
        return back();
    }*/
}
