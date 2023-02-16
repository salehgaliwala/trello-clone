<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Board extends Model
{
    
    //use SoftDeletes;
    public $primaryKey = "board_id";
    protected $fillable = [
        'title', 'description', 'status'
    ];

    public function tasks(){
        return $this->hasMany(Task::class, 'board_id');
    }
}
