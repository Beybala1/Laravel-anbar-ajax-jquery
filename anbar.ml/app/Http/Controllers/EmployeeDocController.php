<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use App\Models\Employee;
use App\Models\EmployeeDoc;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;

class EmployeeDocController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
        $EmployeeDocId = $request->id;

        if($EmployeeDocId){
             
            $EmployeeDoc = EmployeeDoc::find($EmployeeDocId);

            $validator = Validator::make($request->all(),[
                'title' => 'required|min:2',
                'scan'=>'image',
            ]);
  
            if($validator->fails()){
                return response()->json(['status'=>0, 'error'=>$validator->errors()->toArray()]);
            }
            else{
                
                date_default_timezone_set('Asia/Baku');

                if($request->hasFile('scan')){
                    $file = time().'.'.$request->scan->extension();
                    $request->scan->storeAS('uploads/images/',$file);
                    $scan = 'storage/app/uploads/images/'.$file;

                    $path = '/home/filmbaxt/anbar.ml/'.$EmployeeDoc->scan.'';
                    File::delete($path);
                
                    $values = [
                        'title'=>$request->title,
                        'scan' => $scan,
                        'about'=> $request->about,
                        'updated_at'=>date('Y-m-d H:i:s'),
                    ];
                } 
                else{
                   
                    $values = [
                        'title'=>$request->title,
                        'scan'=> $EmployeeDoc->scan,
                        'about'=> $request->about,
                        'updated_at'=>date('Y-m-d H:i:s'),
                    ];
                }
  
                $query = $EmployeeDoc->update($values);

                if( $query ){
                    return response()->json(['status'=>1, 'msg'=>'İşçi uğurla yeniləndi']);
                }
            }
        }
        else{

            $validator = Validator::make($request->all(),[
                'title' => 'required|min:2',
                'scan'=>'required|image'
            ]);
  
            if($validator->fails()){
                return response()->json(['status'=>0, 'error'=>$validator->errors()->toArray()]);
            }
            else{

                date_default_timezone_set('Asia/Baku');

                if($request->hasFile('scan')){

                    $file = time().'.'.$request->scan->extension();
                    $request->scan->storeAS('uploads/images/',$file);
                    $scan = 'storage/app/uploads/images/'.$file;
                }

                $values = [
                    'title'=>$request->title,
                    'scan' => $scan,
                    'about'=> $request->about,
                    'employee_id'=>$request->employee_id,
                    'user_id'=>auth()->id(),	
                    'created_at'=>date('Y-m-d H:i:s'),
                ];

                $query = DB::table('employee_docs')->insert($values);

                if( $query ){
                    return response()->json(['status'=>1, 'msg'=>'İşçinin dokumenti uğurla əlavə edildi']);
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
        if(request()->ajax()) {
            return datatables()->of(EmployeeDoc::join('employees','employees.id','=','employee_docs.employee_id')
            ->select('*','employee_docs.id','employee_docs.created_at as date')
            ->where('employee_docs.user_id','=',auth()->id())
            ->where('employee_docs.employee_id','=',$id)->get())
            ->addColumn('action', 'book-action')
            ->addColumn('image', function($row){
                $image = url($row->scan);
                return $image;
            })
            ->addColumn('created_at', function($row){
                return date('d-m-Y H:i:s', strtotime($row->created_at) );
            })
            ->addIndexColumn()
            ->make(true);
        }
        return view('employee_document',[
            'product_brand'=>Product::where('products.user_id','=',auth()->id())->get(),
            'orders_data'=>Order::join('products','products.id','=','orders.product_id')->where('products.user_id','=',auth()->id())->get(),
            'employees_id'=>Employee::find($id),
        ]);
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
        $EmployeeDoc  = EmployeeDoc::where($where)->first();
     
        return Response()->json($EmployeeDoc);
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
        $query = EmployeeDoc::find($id);
        $path = '/home/filmbaxt/anbar.ml/'.$query->scan.'';

        File::delete($path);
        $query->delete();

        if($query){
            return response()->json(['code'=>1, 'msg'=>'Məlumat  uğurla silindi']);
        }
    }
}
