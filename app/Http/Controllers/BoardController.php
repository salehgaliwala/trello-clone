<?php

namespace App\Http\Controllers;

use App\Board;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class BoardController extends Controller
{
    /**
     * Display a listing of boards and their tasks
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $boards = Board::where('status', '1')->with(['tasks' => function ($q) {
            $q->where('tasks.status', "1")->orderBy('column'); 
        }])->get();
        if($boards){
            $response = response()->json(['responseCode' => '200', 'responseMessage' => 'success', 'data' => $boards]);
        }else{
            $response = response()->json(['responseCode' => '404', 'responseMessage' => 'failure', 'data' => []]);
        }
        return $response;
        
    }

    /**
     * Create a Board
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $insert = Board::create($this->getBoardParams($request));
        if($insert){
            $response = response()->json(['responseCode' => '200', 'responseMessage' => 'created', 'data' => $insert]);
        }else{
            $response = response()->json(['responseCode' => '404', 'responseMessage' => 'failure', 'data' => []]);
        }
        return $response;

    }

    private function getBoardParams($request){
        return [
            'title' => $request->title,
            'description' => $request->description,
            'status' => (isset($request->status)) ? $request->status : "1"
        ];
    }


    /**
     * Display a specified board.
     *
     * @param  \App\Board  $board
     * @return \Illuminate\Http\Response
     */
    public function show(Board $board)
    {
        $boards = Board::where('status', '1')->where('board_id', $board)->with('tasks')->get();
        if($boards){
            $response = response()->json(['responseCode' => '200', 'responseMessage' => 'success', 'data' => $boards]);
        }else{
            $response = response()->json(['responseCode' => '404', 'responseMessage' => 'failure', 'data' => []]);
        }
        return $response;
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Board  $board
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Board $board)
    {
        $update = Board::where('status', '1')->where('board_id',$board->board_id)->update($this->getBoardParams($request));
        if($update){
            $response = response()->json(['responseCode' => '200', 'responseMessage' => 'updated', 'data' => $update]);
        }else{
            $response = response()->json(['responseCode' => '404', 'responseMessage' => 'failure', 'data' => []]);
        }
        return $response;
    }

    /**
     * Remove the specified board from storage.
     *
     * @param  \App\Board  $board
     * @return \Illuminate\Http\Response
     */
    public function destroy(Board $board)
    {
        $delete = Board::where("board_id", $board->board_id)->with('tasks')->delete();
        if($delete){
            $response = response()->json(['responseCode' => '200', 'responseMessage' => 'deleted', 'data' => $delete]);
        }else{
            $response = response()->json(['responseCode' => '404', 'responseMessage' => 'failure', 'data' => []]);
        }
        return $response;
    }


      /**
     * Remove the specified board from storage.
     *
     * @param  \App\Board  $board
     * @return \Illuminate\Http\Response
     */
    public function dump(Request $request)
    {
        $date = date("Y-m-d");
        $user = env('DB_USERNAME');
        $password= env('DB_PASSWORD');
        $dbname=env('DB_DATABASE');
        $path = storage_path().'/db/'.$date.".sql";
        $dump = shell_exec("mysqldump --routines -u $user -p$password $dbname > ". $path);    
        if(file_exists($path)) {
          return response()->download($path);
    }else{
        response()->json(['responseCode' => '404', 'responseMessage' => 'failure', 'data' => []]);
    }
  }
}
