<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class Task extends Model
{
    //use SoftDeletes;
    public $primaryKey = "task_id";
    protected $fillable = [
        'title', 'description', 'board_id'
    ];

    public function board(){
        return $this->belongsTo(Board::class, 'id' );
    }
}
