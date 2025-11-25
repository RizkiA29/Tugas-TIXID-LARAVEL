<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Cinema extends Model
{
    use SoftDeletes;
    use HasFactory;

    protected $fillable = [
        'name',
        'location',
        'seating_capacity',
    ];

    public function screenings()
    {
        return $this->hasMany(Screening::class);
    }
    public function schedules() {
        return $this->hasMany(Schedule::class);
    }
}
