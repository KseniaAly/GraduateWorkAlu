<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserAnswer extends Model
{
    protected $fillable = [
        'question_id', 'user_id', 'test_id', 'answer', 'answers', 'is_correct', 'points',
        'ai_feedback', 'ai_score', 'checked_by_ai'
    ];
    public function test()
    {
        return $this->belongsTo(Test::class, 'test_id');
    }
    public function user(){
        return $this->belongsTo(User::class, 'user_id');
    }
    public function question(){
        return $this->belongsTo(Question::class, 'question_id');
    }
}
