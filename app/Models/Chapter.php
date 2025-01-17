<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Chapter extends Model
{
    use HasFactory;

    // use SoftDeletes;

    protected $fillable = [

        'subject_id',
        'name'

    ];

    public function subject()
    {
        return $this->belongsTo(Subject::class);

    }

    public function classroom()
    {
        return $this->belongsTo(Classroom::class);

    }

    public function classes()
    {
        return $this->belongsToMany(Classroom::class, 'chapter_class', 'chapter_id', 'classroom_id');
    }

    public function subjects()
    {
        return $this->belongsToMany(Subject::class, 'chapter_subject', 'chapter_id', 'subject_id');
    }
   public function chapters()
    {
        return $this->belongsToMany(Chapter::class, 'chapter_subject', 'subject_id', 'chapter_id');
    }

    // public function topics()
    // {
    //     return $this->hasMany(Topic::class);
    // }

    public function topics()
    {
        return $this->belongsToMany(Topic::class, 'topic_chapter');
    }

//    public function topics()
//    {
//        return $this->belongsToMany(Topic::class, 'topic_chapter', 'chapter_id', 'topic_id');
//    }

//    public function topics()
//     {
//         return $this->hasMany(Topic::class);
//     }
//     public function topics()
// {
//     return $this->belongsToMany(Topic::class, 'topic_chapter', 'chapter_id', 'topic_id');
// }

    // public function mcqs()
    // {
    //     return $this->hasMany(Mcqs::class);
    // }

    public function mcqs()
    {
        return $this->belongsToMany(MCQs::class);
    }
}
