<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use PharIo\Manifest\Author;

class Test extends Model
{
    protected $fillable = [
        'title', 'description', 'limit_time', 'vacancy_id',
        'passing_score', 'status'
    ];

    public function vacancy(){
        return $this->belongsTo(Vacancy::class);
    }
    public function author()
    {
        return $this->belongsTo(User::class);
    }

    public function questions()
    {
        return $this->belongsToMany(
            Question::class,
            'test_questions'
        )
            ->withPivot('position')
            ->orderBy('position');
    }
}
