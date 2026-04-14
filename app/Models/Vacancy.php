<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Vacancy extends Model
{
    protected $fillable = [
        'title',
        'salary',
        'description',
        'type'
    ];

    public function tests()
    {
        return $this->hasMany(Test::class);
    }
}
