<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class ProfileController extends Controller
{
    public function index(){
        return view('auth.profile');
    }

    public function update(Request $request){

        $user = User::find(auth()->id());
    
        $validator = Validator::make($request->all(),[
            'name' => 'required|min:3',
            'email'=>'required|email',
            'password'=>'required'
        ],$messages = array(
            'name.required' => 'İstifadəçi xanasını boş buraxmayın',
            'name.min' => 'İstifadəçi adı 3 simvoldan aşağı ola bilməz',
            'email.required' => 'Email xanasını boş buraxmayın',
            'email.email' => 'Daxil etdiyiniz məlumat email formatında deyil',
            'password.required' => 'Parol xanasını boş buraxmayın',
            'password.min' => 'Parol 6 simvoldan aşağı ola bilməz',
        ));
    
        if($validator->fails()){
            return response()->json(['status'=>0, 'error'=>$validator->errors()->toArray()]);
        }
        else{

            if(Hash::check($request->password,$user->password)){

                if(empty($request->password_new) or strlen($request->password_new)>5){

                    if($request->password_new==$request->password_confirmation){
                
                        date_default_timezone_set('Asia/Baku');
                
                        if($request->hasFile('logo')){
                            $file = time().'.'.$request->logo->extension();
                            $request->logo->storeAS('uploads/images/',$file);
                            $user->logo = 'storage/app/uploads/images/'.$file;
                
                            $values = [
                                'name'=>$request->name,
                                'email'=>$request->email,
                                'updated_at'=>date('Y-m-d H:i:s'),
                            ];
                        } 
                        
                        else{
                            $values = [
                                'name'=>$request->name,
                                'email'=>$request->email,
                                'logo' => $user->logo,
                                'updated_at'=>date('Y-m-d H:i:s'),
                            ];
                        }

                        if(strlen($request->password_new)>5){
                            $user->password = Hash::make($request->password_new);
                        }

                        $query = $user->update($values);

                        if( $query ){
                            return response()->json(['status'=>1, 'msg'=>'Profil uğurla yeniləndi']);
                        }

                    }else{return response()->json(['status'=>4, 'msg'=>'Yeni parol ilə təkrar parol uyğun deyil']);}
                }else{return response()->json(['status'=>3, 'msg'=>'Yeni parol 6 simvoldan az ola bilməz']);}
            }else{return response()->json(['status'=>2, 'msg'=>'Daxil etdiyiniz parol yanlışdır']);}
        }
    }
}
