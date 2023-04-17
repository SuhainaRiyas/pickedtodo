<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Validator;
use Hash;

class UserController extends Controller
{
    public function register(Request $request){
      $validator =   Validator::make($request->all(),[
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:6',
            'phone' => 'required|numeric',
        ]);
        return response()->json(['status'=>'error','validerrors'=>$validator->messages()]);
        if($validator->fails()){
            return response()->json(['status'=>'error','validerrors'=>$validator->messages()]);
        }

        $user = User::where('email',$request->email)->first();

        if(!empty($user)){
            return response()->json(['status'=>'error','msg'=>'Email Id already taken']);
        }

        $request->password = Hash::make($request->password);

        $result = User::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'password' => $request->password,
            'phone' => $request->phone,
        ]);

        if($result){
            return response()->json(['status'=>'success','msg'=>'Registered successfully','info'=>['first_name'=>$result->first_name,'last_name'=>$result->last_name,'email'=>$result->email]]);
        }else{
            return response()->json(['status'=>'error','msg'=>'Failed to create new user']);
        }
    }

    public function login(Request $request){

        $validator =   Validator::make($request->all(),[
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if($validator->fails()){
            return response()->json(['status'=>'error','validerrors'=>$validator->messages(),'msg'=>'Validation error']);
        }
        $user = User::where('email',$request->email)->first();
        if(!$user){
            return response()->json(['status'=>'error','msg'=>'Email Id is not registered']);
        }

       $password =  Hash::check($request->password,$user->password);
       if($password){
        return response()->json(['status'=>'success','msg'=>'Logged In successfully','info'=>['first_name'=>$user->first_name,'last_name'=>$user->last_name,'email'=>$user->email]]);
       }else{
        return response()->json(['status'=>'error','msg'=>'Incorrect password']);
       }
    }
}
