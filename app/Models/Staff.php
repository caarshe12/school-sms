<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Staff extends Model
{
    use HasFactory;

    protected $fillable = ['first_name', 'last_name', 'email', 'department', 'role'];

    public function user()
    {
        return $this->belongsTo(User::class, 'email', 'email');
    }

    public function schoolClass()
    {
        return $this->hasOne(SchoolClass::class, 'teacher_id');
    }
}
