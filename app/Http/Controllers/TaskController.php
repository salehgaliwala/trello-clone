<?php

namespace App\Http\Controllers;

use App\Task;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    /**
     * Display a listing of Tasks and their tasks
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $Tasks = Task::where('status', '1')->with('board')->get();
        if($Tasks){
            $response = response()->json(['responseCode' => '200', 'responseMessage' => 'success', 'data' => $Tasks]);
        }else{
            $response = response()->json(['responseCode' => '404', 'responseMessage' => 'failure', 'data' => []]);
        }
        return $response;
        
    }

    /**
     * Create a Task
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $insert = $this->storeTaskParams($request);
        if($insert){
            $response = response()->json(['responseCode' => '200', 'responseMessage' => 'created', 'data' => $insert]);
        }else{
            $response = response()->json(['responseCode' => '404', 'responseMessage' => 'failure', 'data' => []]);
        }

        return $response;

    }

    private function storeTaskParams($request){
            $task = new Task();
            $task->title = $request->title;
            $task->description = $request->description;
            $task->board_id = $request->board_id;
            $task->status = (isset($request->status)) ? $request->status : "1";
            if($task->save()){
                $this->setColumnCreateParams($task);
                return true;
            }else{
                return false;
            }
        
    }


    private function setColumnCreateParams($task){
        $getDataSet =  Task::where('status', '1')->where('board_id', $task->board_id)->get()->toArray();
        $getUpdateSet = Task::where('status', '1')->where('board_id', $task->board_id)->where('task_id', $task->task_id);
        $count = count($getDataSet);
        if($count > 1){
            $lastButOne = $count-2;
                $update =  $getUpdateSet->update([
                    'column' =>$getDataSet[$lastButOne]['column'] + 1,
                ]);
        }else{
                $update =  $getUpdateSet->update([
                    'column' => 1,
                ]);
        }

    }

    /**
     * Display a specified Task.
     *
     * @param  \App\Task  $Task
     * @return \Illuminate\Http\Response
     */
    public function show(Task $Task)
    {
        $Tasks = Task::where('status', '1')->where('task_id', $Task)->with('tasks')->first();
        if($Tasks){
            $response = response()->json(['responseCode' => '200', 'responseMessage' => 'success', 'data' => $Tasks]);
        }else{
            $response = response()->json(['responseCode' => '404', 'responseMessage' => 'failure', 'data' => []]);
        }

        return $response;
    }


    /**
     * Update the specified task in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Task  $Task
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Task $Task)
    {
        $update = Task::where('status', '1')->where('task_id', $Task->task_id)->update($this->getTaskParams($request));
        if($update){
            $response = response()->json(['responseCode' => '200', 'responseMessage' => 'updated', 'data' => $update]);
        }else{
            $response = response()->json(['responseCode' => '404', 'responseMessage' => 'failure', 'data' => []]);
        }
        return $response;
    }


    private function getTaskParams($request){
      return  [ 
                'title' => $request->title,
                'description' => $request->description,
                'status' => (isset($request->status)) ? (string) $request->status : "1"];
}



    /**
     * Update the column specified task in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Task  $Task
     * @return \Illuminate\Http\Response
     */
    public function updateColumn(Request $request, Task $Task)
    {
        $update = $this->setColumnParams($request, $Task);
        if($update){
            $response = response()->json(['responseCode' => '200', 'responseMessage' => 'updated', 'data' => $update]);
        }else{
            $response = response()->json(['responseCode' => '404', 'responseMessage' => 'failure', 'data' => []]);
        }
        return $response;
    }

    private function setColumnParams($request, $task){
        $getColumn =  Task::where('status', '1')->where('board_id', $task->board_id)->get()->toArray();
        $data = [];
        for($i = 0; $i < count($getColumn); $i++){
            //if column is equal to up....
            //get column value
            //subtract column value by 1......column - 1
            //search for the value of subraction in the table and add value of sub... field + 1
            //and save both values
                if($request->column == "up"){
                    if($getColumn[$i]['task_id'] == $task->task_id){
                        if($getColumn[$i]['column'] > 1){
                            $valueOfSubtraction = $getColumn[$i]['column'] - 1;   
                            $addValueOfSubtraction = $this->addValueOfSubtractionToField($getColumn[$i]['column'],$valueOfSubtraction, $task->task_id, "up");
                            $updateColumnWithValue = $this->updateColumnField($task, $getColumn[$i]['task_id'],$valueOfSubtraction, "up");
                        }
                    }
                }else{
                    if($getColumn[$i]['task_id'] == $task->task_id){
                       
                            $valueOfAddition = $getColumn[$i]['column'] + 1;   
                            $addValueOfSubtraction = $this->addValueOfSubtractionToField($getColumn[$i]['column'],$valueOfAddition, $task->task_id, "down");
                            $updateColumnWithValue = $this->updateColumnField($task, $getColumn[$i]['task_id'],$valueOfAddition, "down");
                }
            }

        }
                    return true;
    }

    private function updateColumnField($task, $task_id,$column, $type){
        if($type == "up"){
            $model = Task::where('board_id', $task->board_id)->where('column', $column)->where("status", "1")->first();
            $model->column += 1;
            $model->save();
        }else{
            $model = Task::where('board_id', $task->board_id)->where('column', $column)->where("status", "1")->orderBy('column', 'desc')->first();
            $model->column -= 1;
            $model->save();
        }
        
    }

    private function addValueOfSubtractionToField($column, $valueOfSubtraction, $task, $type){
       //dd("coulmn: ".$column, "subtract: ".$valueOfSubtraction, "task: ".$task, "type: ". $type);
        $getUpdateColumn =  Task::where('status', '1')->where('column', $column)->where('task_id', $task)->update([
            'column' => ($type == "up") ? $column - 1 : $column + 1,
        ]);
    }
   

    /**
     * Remove the specified Task from storage.
     *
     * @param  \App\Task  $Task
     * @return \Illuminate\Http\Response
     */
    public function destroy(Task $Task)
    {
        $delete = Task::destroy($Task);
        if($delete){
            $response = response()->json(['responseCode' => '200', 'responseMessage' => 'deleted', 'data' => $delete]);
        }else{
            $response = response()->json(['responseCode' => '404', 'responseMessage' => 'failure', 'data' => []]);
        }

        return $response;
    }
}
