<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QuestionCategory extends Model
{
    public function questions(){
        return $this->hasMany(Question::class);
    }
}
