<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Classroom extends Model
{
    use HasFactory;

    use SoftDeletes;

    protected $fillable = [

        'board_id',
        'name'

    ];

    // public function boards()
    // {
    //     return $this->belongsToMany(Board::class);
    // }

    public function subjects()
    {
        return $this->belongsToMany(Subject::class);
    }

    public function board()
    {
        return $this->belongsTo(Board::class);
    }
    public function boards()
    {
        return $this->belongsToMany(Board::class, 'board_classroom', 'classroom_id', 'board_id');
    }

    public function mcqs()
    {
        return $this->belongsToMany(MCQs::class);
    }
}
