<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Topic extends Model
{
    protected $table = 'topics';

    use HasFactory;

    use SoftDeletes;

    protected $fillable = [

        'chapter_id',
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

    public function chapter()
    {
        return $this->belongsTo(Chapter::class);
    }

    // public function chapters()
    // {
    //     return $this->belongsToMany(Chapter::class, 'topic_chapter', 'topic_id','chapter_id');
    // }

    public function classes()
    {
        return $this->belongsToMany(Classroom::class, 'topic_class', 'topic_id', 'classroom_id');
    }

    public function subjects()
    {
        return $this->belongsToMany(Subject::class, 'topic_subject', 'topic_id', 'subject_id');
    }
    
    public function chapters()
    {
        return $this->belongsToMany(Chapter::class, 'topic_chapter', 'topic_id', 'chapter_id');
    }

    public function mcqs()
    {
        return $this->belongsToMany(MCQs::class);
    }
}
