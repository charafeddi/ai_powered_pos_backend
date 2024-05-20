<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Todo;
use Illuminate\Support\Facades\Validator;


class TodoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($user_id)
    {
        //
        $todos = Todo::where('user_id',$user_id)->get();
        $message=[];
        foreach($todos  as $todo){
            $message[] = [
                'id' => $todo->id,
                'text' => $todo->task,
                'completed' => $todo->done == 1 ? true : false,
                'aClass' => $todo->done == 1 ? 'done' : '',            
            ];
        }
        return response()->json($message);
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
        //
        $validator = Validator::make($request->all(), [
            'task' => 'required|string|max:255',
            'done' => 'boolean',
            'user_id' => 'required|integer|exists:users,id',
        ]);

        if($validator ->fails()){
            return response()->json([
                'message' => 'supplier validator failed ', 
                'error' => $validator->errors(),
            ],400);
        }

        Todo::create(
            $validator->validated()
        );

        return response()->json([
            'message' => ' Task created Successfully',
        ], 201);
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
        $todos = Todo::find($id);
      
        
        $message= [
            'id' => $todo->id,
            'text' => $todo->task,
            'completed' => $todo->done == 1 ? true : false,
            'aClass' => $todo->done == 1 ? 'done' : '',            
        ];
        
        return response()->json($message);
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
        //  
        $validator = Validator::make($request->all(), [
            'task' => 'required|string|max:255',
            'done' => 'boolean',
            'user_id' => 'required|integer|exists:users,id',
        ]);

        if($validator ->fails()){
            return response()->json([
                'message' => 'supplier validator failed ', 
                'error' => $validator->errors(),
            ],400);
        }

        $todo = Todo::find($id);

        $todo->task = $request->input('task');
        $todo->done = $request->input('done');
        $todo->user_id = $request->input('user_id');
        $todo->save();

        return response()->json([
            'message' => ' Todos Updated Successfully ',
        ], 201);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        $todo = Todo::find($id);
        $todo->delete();

        return response()->json([
            'message' => ' Todos Deleted Succesfully',
        ],201);
    }

    /**
     * get all the removed todo from storage
     * 
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function importDeletedTodos($user_id)
    {
        $deletedTodos = Todo::where('user_id',$user_id)->withTrashed()
                                ->whereNotNull('deleted_at')
                                ->get();

         $message=[];
        foreach($deletedTodos  as $todo){
            $message[] = [
                'id' => $todo->id,
                'text' => $todo->task,
                'completed' => $todo->done == 1 ? true : false,
                'aClass' => $todo->done == 1 ? 'done' : '',            
            ];
        }
        return response()->json($message);
    }

    public function importFinishedTodos($user_id){
        $finishedTodos = Todo::where('user_id', $user_id)->where('done',1)->get();
        
        $message=[];
        foreach($finishedTodos  as $todo){
            $message[] = [
                'id' => $todo->id,
                'text' => $todo->task,
                'completed' => $todo->done == 1 ? true : false,
                'aClass' => $todo->done == 1 ? 'done' : '',            
            ];
        }
        return response()->json($message);
    }
}
