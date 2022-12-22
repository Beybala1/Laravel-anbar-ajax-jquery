<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeeDoc extends Model
{
    use HasFactory;

    protected $fillable = ['title','about','scan'];
}
