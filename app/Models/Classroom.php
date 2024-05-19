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

    public function boards()
    {
        return $this->belongsToMany(Board::class);
    }

    public function subjects()
    {
        return $this->belongsToMany(Subject::class);
    }

}
