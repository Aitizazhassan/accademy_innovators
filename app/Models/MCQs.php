<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MCQs extends Model
{
    use HasFactory;
    protected $fillable = [
        'country_id',
        'board_id',
        'subject_id',
        'chapter_id',
        'topic_id',
        'class_id',
        'statement',
        'optionA',
        'optionB',
        'optionC',
        'optionD',
        'solution_link_english',
        'solution_link_urdu',
    ];

    // public function country()
    // {
    //     return $this->belongsTo(Country::class);
    // }

    // public function board()
    // {
    //     return $this->belongsTo(Board::class);
    // }

    // public function class()
    // {
    //     return $this->belongsTo(Classroom::class);
    // }

    // public function subject()
    // {
    //     return $this->belongsTo(Subject::class);
    // }

    // public function chapter()
    // {
    //     return $this->belongsTo(Chapter::class);
    // }

    // public function topic()
    // {
    //     return $this->belongsTo(Topic::class);
    // }

    public function countries() {
        return $this->belongsToMany(Country::class, 'mcq_country', 'm_c_q_id', 'country_id');
    }
    
    public function boards() {
        return $this->belongsToMany(Board::class, 'mcq_board', 'm_c_q_id', 'board_id');
    }
    
    public function classes() {
        return $this->belongsToMany(Classroom::class, 'mcq_class', 'm_c_q_id', 'classroom_id');
    }
    
    public function subjects() {
        return $this->belongsToMany(Subject::class, 'mcq_subject', 'm_c_q_id', 'subject_id');
    }
    
    public function chapters() {
        return $this->belongsToMany(Chapter::class, 'mcq_chapter', 'm_c_q_id', 'chapter_id');
    }
    
    public function topics() {
        return $this->belongsToMany(Topic::class, 'mcq_topic', 'm_c_q_id', 'topic_id');
    }
}
