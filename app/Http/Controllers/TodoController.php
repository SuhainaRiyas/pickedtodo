<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Todo;
use App\Models\User;
use Validator;
use Carbon\Carbon;

class TodoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $user = User::where('email',$request->email)->first();
        if($user){
            $pending = Todo::where('user_id',$user->id)->where('is_completed','0')->orderBy('id','desc')->get();
            $completed = Todo::where('user_id',$user->id)->where('is_completed','1')->orderBy('id','desc')->get();

            return response()->json(['status'=>'success', 'pending' =>$pending, 'completed' =>$completed]);
        }else{
            return response()->json(['status'=>'failed','msg'=>'User not found']);
        }

       
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'title' => 'required',
            'description' => 'required',
        ]);

        if($validator->fails()){
            return response()->json(['status'=>'error','errors'=>$validator->messages()]);
        }

        $user = User::where('email',$request->email)->first();

        if($user){
            $data = Todo::create([
                'user_id' => $user->id,
                'title'=>$request->title,
                'description' => $request->description,
            ]);
        }else{
            return response()->json(['status'=>'failed','msg'=>'User not found']);
        }
       

        if($data){
            return response()->json(['status'=>'success','msg'=>'Todo created successfully']);
        }else{
            return response()->json(['status'=>'failed','msg'=>'Failed to save']);
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
        //
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
        $validator = Validator::make($request->all(),[
            'title' => 'required',
            'description' => 'required',
        ]);

        if($validator->fails()){
            return response()->json(['status'=>'error','errors'=>$validator->messages()]);
        }

        $todo = Todo::find($id);

        if($todo){
            $data = $todo->update([
                'title'=>$request->title,
                'description' => $request->description
            ]);
        }else{
            return response()->json(['status'=>'failed','msg'=>'Task not found']);
        }
       

        if($data){
            return response()->json(['status'=>'success','msg'=>'Todo updated successfully']);
        }else{
            return response()->json(['status'=>'failed','msg'=>'Failed to save']);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
         $todo = Todo::find($id);

         if($todo){
            $todo->delete();
            return response()->json(['status'=>'success','msg'=>'Todo deleted successfully']);
        }else{
            return response()->json(['status'=>'failed','msg'=>'Todo not found']);
        }
    }

    public function markCompleted(Request $request){
        $todo = Todo::find($request->id);

        if($todo){
                $todo->update([
                    'is_completed'=>'1',
                    'completed_at'=>Carbon::now()
                ]);
                return response()->json(['status'=>'success','msg'=>'Todo marked as completed']);
        }else{
            return response()->json(['status'=>'failed','msg'=>'Todo not found']);
        }
    }
    public function markNotCompleted(Request $request){
        $todo = Todo::find($request->id);

        if($todo){
                $todo->update([
                    'is_completed'=>'0',
                    'completed_at'=>Carbon::now()
                ]);
                return response()->json(['status'=>'success','msg'=>'Todo marked as not completed']);
        }else{
            return response()->json(['status'=>'failed','msg'=>'Todo not found']);
        }
    }
}
