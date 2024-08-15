<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cources_time extends Model
{
    use HasFactory;
    protected $fillable=[
        'course_id',
        'day_of_month',
        'start_time',
        'end_time'
    ];

    public function course()
    {
        return $this->belongsTo(Cources::class);
    }
}
