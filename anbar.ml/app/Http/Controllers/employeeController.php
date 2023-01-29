<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Order;
use App\Models\Employee;
use Illuminate\Support\Facades\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class EmployeeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(request()->ajax()) {
            return datatables()->of(Employee::select('*')->where('user_id','=',auth()->id()))
            ->addColumn('action',  function($row){
                return '
                <a href="javascript:void(0)" data-toggle="tooltip" title="Redaktə et" data-id="'.$row->id.'" data-original-title="Edit" class="edit btn btn-success pb-1 pt-1 edit edit-product">
                    <i class="bi bi-pencil-square"></i>
                </a>

                <a href="javascript:void(0);" id="delete-book" title="Sil" data-toggle="tooltip" data-original-title="Delete" data-id="'.$row->id.'" class="delete btn btn-danger pb-1 pt-1">
                    <i class="bi bi-x-lg"></i>
                </a>

                <a href="/employee_document/'.$row->id.'" id="" data-toggle="tooltip" title="Dokumentlərə bax" data-original-title="Document" data-id="'.$row->id.'" class="btn btn-primary pb-1 pt-1">
                    <i class="bi bi-file-text"></i>
                </a>';
            })
            ->addColumn('created_at', function($row){
                return date('d-m-Y H:i:s', strtotime($row->created_at) );
            })
            ->addIndexColumn()
            ->make(true);
        }
        return view('employee',[
            'data_list'=>Employee::get()->where('user_id','=',auth()->id()),
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
        $EmployeeId = $request->id;

        if($EmployeeId){
             
            $Employee = Employee::find($EmployeeId);

            $validator = Validator::make($request->all(),[
                'name' => 'required|min:3',
                'lastname'=>'required|min:3',
                'phone'=>'required|min:3',
                'email'=>'required|min:3',
                'hired'=>'required',
                'salary'=>'required',
                'job'=>'required',
                'logo'=>'image',
            ]);
  
            if($validator->fails()){
                return response()->json(['status'=>0, 'error'=>$validator->errors()->toArray()]);
            }
            else{
                
                date_default_timezone_set('Asia/Baku');

                if($request->hasFile('logo')){
                    
                    $path = '/home/filmbaxt/anbar.ml/'.$Employee->logo.'';
                    File::delete($path);

                    $file = time().'.'.$request->logo->extension();
                    $request->logo->storeAS('uploads/images/',$file);
                    $Employee -> logo = 'storage/app/uploads/images/'.$file;
                }   

                $Employee->name = $request->name;
                $Employee->lastname = $request->lastname;
                $Employee->phone = $request->phone;
                $Employee->email = $request->email;
                $Employee->hired = $request->hired;
                $Employee->salary = $request->salary;
                $Employee->job = $request->job;
                $query = $Employee->save();

                if( $query ){
                    return response()->json(['status'=>1, 'msg'=>'İşçi uğurla yeniləndi']);
                }
            }
        }
        else{

            $validator = Validator::make($request->all(),[
                'name' => 'required|min:3',
                'lastname' => 'required|min:3',
                'phone' => 'required|unique:employees|min:10',
                'email' => 'required|unique:employees|email',
                'hired' => 'required',
                'salary' => 'required',
                'job' => 'required',
                'logo'=>'required|image',
            ],array(
                'name.required' => 'Ad xanasını boş buraxmayın',
                'name.min' => 'Ad 3 simvoldan aşağı ola bilməz',
                'lastname.required' => 'Soyad xanasını boş buraxmayın',
                'lastname.min' => 'Soyad 3 simvoldan aşağı ola bilməz',
                'phone.required' => 'Telefon xanasını boş buraxmayın',
                'phone.min' => 'Telefon 10 simvoldan aşağı ola bilməz',
                'phone.unique' => 'Bu telefon nömrəsi mövcuddur',
                'email.required' => 'Email xanasını boş buraxmayın',
                'email.email' => 'Daxil etdiyiniz məlumat email formatında deyil',
                'email.unique' => 'Bu email mövcuddur',
                'hired.required' => 'İşə qəbul tarixini boş buraxmayın',
                'salary.required' => 'Maaş xanasını boş buraxmayın',
                'job.required' => 'Vəzifə xanasını boş buraxmayın',
                'logo.required' => 'Logo xanasını boş buraxmayın',
                'logo.image' => 'Logo şəkil formatında deyil',
            ));
  
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
                    'name'=>$request->name,
                    'lastname'=>$request->lastname,
                    'phone'=>$request->phone,
                    'email'=>$request->email,
                    'hired'=>$request->hired,
                    'salary'=>$request->salary,
                    'job'=>$request->job,
                    'logo' => $logo,
                    'user_id'=>auth()->id(),
                    'created_at'=>date('Y-m-d H:i:s'),
                ];

                $query = DB::table('employees')->insert($values);

                if( $query ){
                    return response()->json(['status'=>1, 'msg'=>'İşçi uğurla əlavə edildi']);
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
        $Employee  = Employee::where($where)->first();
     
        return Response()->json($Employee);
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
        $query = Employee::find($id);
        $path = '/home/filmbaxt/anbar.ml/'.$query->logo.'';
        File::delete($path);
        $query->delete();
        
        if($query){
            return response()->json(['code'=>1, 'msg'=>'Məlumat  uğurla silindi']);
        }
    }
}
