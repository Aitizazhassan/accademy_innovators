<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Subject extends Model
{
    use HasFactory;

    use SoftDeletes;

    protected $fillable = [

        'classroom_id',
        'name'

    ];


    public function subjectClassroom()
    {
        return $this->belongsTo(Classroom::class,'classroom_id','id')->withDefault([
            'name' => ''
        ]);

    }

    public function classroom()
    {
        return $this->belongsTo(Classroom::class);

    }

    public function scopeClassRoomSubjects($query)
    {

        return $query->whereNotNull('classroom_id');

    }

    public function classrooms()
    {
        return $this->belongsToMany(Classroom::class);
    }

    // public function chapters()
    // {
    //     return $this->belongsToMany(Chapter::class, 'chapter_subject', 'subject_id', 'chapter_id');
    // }

    public function class()
    {
        return $this->belongsTo(Classroom::class);
    }

    public function chapters()
    {
        return $this->hasMany(Chapter::class);
    }

}
