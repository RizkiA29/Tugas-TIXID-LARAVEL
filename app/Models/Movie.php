<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Movie extends Model
{
    use SoftDeletes;
    protected $fillable = ['title', 'description', 'duration', 'age_rating', 'genre', 'director', 'poster', 'actived'];
    public function schedules() {
        return $this->hasMany(Schedule::class);
    }
}
