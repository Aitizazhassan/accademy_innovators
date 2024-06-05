<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MCQs extends Model
{
    use HasFactory;
    protected $fillable = [
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
    public function board()
    {
        return $this->belongsTo(Board::class);
    }

    public function class()
    {
        return $this->belongsTo(Classroom::class);
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    public function chapter()
    {
        return $this->belongsTo(Chapter::class);
    }

    public function topic()
    {
        return $this->belongsTo(Topic::class);
    }
}
