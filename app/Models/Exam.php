<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Exam extends Model
{
    use HasFactory;

    protected $fillable = ['school_class_id', 'name', 'subject', 'date'];

    public function schoolClass()
    {
        return $this->belongsTo(SchoolClass::class);
    }

    public function records()
    {
        return $this->hasMany(ExamRecord::class);
    }
}
