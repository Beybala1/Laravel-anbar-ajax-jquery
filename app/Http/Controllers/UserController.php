<?php

namespace App\Http\Controllers;

use Validator;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\Validated;


class UserController extends Controller
{
    public function create(){
        return view('auth.register');
    }

   public function store(Request $request){
    /*
        $form = $request->validate([
            'name'=>'required|min:3',
            'email'=>'required|email|unique:users',
            'password'=>'required|confirmed|min:6',
        ]);

        $form['password'] = bcrypt($form['password']);

        User::create($form);

        return redirect('/')->with('success','Qeydiyyat uğurla tamamlandı');*/

        $validator = Validator::make($request->all(),[
            'name'=>'required|min:3',
            'email'=>'required|email|unique:users',
            'password'=>'required|confirmed|min:6',
        ],
        $messages = array(
            'name.required' => 'İstifadəçi xanasını boş buraxmayın',
            'name.min' => 'İstifadəçi adı 3 simvoldan aşağı ola bilməz',
            'email.required' => 'Email xanasını boş buraxmayın',
            'email.email' => 'Daxil etdiyiniz məlumat email formatında deyil',
            'email.unique' => 'Bu email mövcuddur',
            'password.required' => 'Parol xanasını boş buraxmayın',
            'password.confirmed' => 'Parol ilə təkrar parol uyğun deyil',
            'password.min' => 'Parol 6 simvoldan aşağı ola bilməz',
        ));

        if(!$validator->passes()){
            return response()->json(['status'=>0, 'error'=>$validator->errors()->toArray()]);
        }
        else{
            $values = [
                 'name'=>$request->name,
                 'email'=>$request->email,
                 'password'=>Hash::make($request->password),
                 'logo'=>'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQP3lC0SfgqCcTGipFh64hddM6xgBYQj90wOA&usqp=CAU'
            ];

            $query = DB::table('users')->insert($values);

            if( $query ){
                return response()->json(['status'=>1, 'msg'=>'Qeydiyyat uğurla tamamlandı']);
                //return redirect('/')->with('success','Qeydiyyat uğurla tamamlandı');
            }
        }
    }

    public function logout(){
        auth()->logout();

        session()->invalidate();
        session()->regenerateToken();

        return redirect('/login');
    }

    public function login(){
        return view('auth.login');
    }

    public function authenticate(Request $request){
      
        $this->validate($request,[
            'email'=>'required|email',
            'password'=>'required',
        ]);

        if(!auth()->attempt($request->only('email','password'),$request->remember)){
            return back()->with('fail','Email və ya parol yanlışdır');
        }

        return redirect('/'); 

        /*$validator = Validator::make($request->all(),[
            'email'=>'required|email',
            'password'=>'required',
        ],
        $messages = array(
            'email.required' => 'Email xanasını boş buraxmayın',
            'email.email' => 'Daxil etdiyiniz məlumat email formatında deyil',
            'password.required' => 'Parol xanasını boş buraxmayın',
        ));

        if(!$validator->passes()){
            return response()->json(['status'=>0, 'error'=>$validator->errors()->toArray()]);
        }
        else{
            if(!auth()->attempt($request->only('email','password'),$request->remember)){
                //return back()->with('fail','Email və ya parol yanlışdır');
                return response()->json(['status'=>0, 'msg'=>'Uğurli login']);
            }
        }*/
    }
}
