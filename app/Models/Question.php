<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    public function test_questions()
    {
        return $this->hasMany(TestQuestion::class);
    }
    public function category()
    {
        return $this->belongsTo(QuestionCategory::class, 'question_category_id');
    }
    public function options(){
        return $this->hasMany(QuestionOption::class);
    }
    public function answers()
    {
        return $this->hasMany(UserAnswer::class, 'question_id');
    }
}
