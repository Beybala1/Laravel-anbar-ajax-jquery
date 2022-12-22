<?php

namespace App\Http\Controllers;

use Validator;
use Datatables;
use App\Models\Brand;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class BrandController extends Controller
{
    public function index()
    {
        if(request()->ajax()) {
            return datatables()->of(Brand::select('*')->latest()->where('user_id','=',auth()->id()))
            ->addColumn('action',  function($row){
                $count = Product::where('brand_id','=',$row->id)->count();

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
                return date('d-m-Y H:i:s', strtotime($row->created_at) );
            })
            ->addColumn('logo', function($row){
                return $row->logo;
            })
            ->addIndexColumn()
            ->make(true);
        }
        return view('brand',[
            'data_list'=>Brand::where('user_id','=',auth()->id())->get(),
            'data_product'=>Product::where('user_id','=',auth()->id())->get(),
            'product_brand'=>Product::where('products.user_id','=',auth()->id())->get(),
            'orders_data'=>Order::join('products','products.id','=','orders.product_id')->where('products.user_id','=',auth()->id())->get(),
        ]);
        $brand_count = Brand::where('user_id','=',auth()->id())->count();

        return response()->json([
            'brand_count' => $brand_count
        ]);
    }

    public function store(Request $request)
    {  

        $BrandId = $request->id;

        if($BrandId){
             
            $Brand = Brand::find($BrandId);

            $validator = Validator::make($request->all(),[
                'brend' => 'required|min:2',
                'logo'=>'image'
            ]);
  
            if(!$validator->passes()){
                return response()->json(['status'=>0, 'error'=>$validator->errors()->toArray()]);
            }
            else{
                
                date_default_timezone_set('Asia/Baku');

                if($request->hasFile('logo')){
                    
                    $file = time().'.'.$request->logo->extension();
                    $request->logo->storeAS('uploads/images/',$file);
                    $logo = 'storage/app/uploads/images/'.$file;

                    $path = '/home/filmbaxt/anbar.ml/'.$Brand->logo.'';
                    File::delete($path);

                    $values = [
                        'brend'=>$request->brend,
                        'logo' => $logo,
                        'updated_at'=>date('Y-m-d H:i:s'),
                    ];
                } 
                
                else{
                    $values = [
                        'brend'=>$request->brend,
                        'logo' => $Brand->logo,
                        'updated_at'=>date('Y-m-d H:i:s'),
                    ];
                }
  
                $query = $Brand->update($values);

                if( $query ){
                    return response()->json(['status'=>1, 'msg'=>'Brend uğurla yeniləndi']);
                }
            }
        }
        else{

            $validator = Validator::make($request->all(),[
                'brend' => 'required|min:2|unique:brands,brend,NULL,id,user_id,'.Auth::id().'',
                'logo' => 'required|image',
            ]);
  
            if(!$validator->passes()){
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
                        'brend'=>$request->brend,
                        'logo' => $logo,
                        'user_id'=>auth()->id(),
                        'created_at'=>date('Y-m-d H:i:s'),
                    ];

                    $query = DB::table('brands')->insert($values);

                    if( $query ){
                        return response()->json(['status'=>1, 'msg'=>'Brend uğurla əlavə edildi']);
                    }
                
            }
        }
    }
     
    public function edit(Request $request)
    {   
        $where = array('id' => $request->id);
        $Brand  = Brand::where($where)->first();
     
        return Response()->json($Brand);

    }
     
    public function destroy(Request $request)
    {
        $query = Brand::find($request->id);
        $path = '/home/filmbaxt/anbar.ml/'.$query->logo.'';

        File::delete($path);
        $query->delete();

        if($query){
            return response()->json(['code'=>1, 'msg'=>'Məlumat uğurla silindi']);
        }    
    }
}