<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Board extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'country_id'

    ];

    public function countries()
    {
        return $this->belongsToMany(Country::class);
    }

    public function classes()
    {
        return $this->hasMany(Classroom::class);
    }

    public function classrooms()
    {
        return $this->belongsToMany(Classroom::class, 'board_classroom', 'board_id', 'classroom_id');
    }

    public function mcqs()
    {
        return $this->belongsToMany(MCQs::class);
    }

}
