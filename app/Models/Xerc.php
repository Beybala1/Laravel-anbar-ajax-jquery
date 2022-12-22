<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Xerc extends Model
{
    use HasFactory;
    
    protected $fillable = ['xerc_product', 'count'];
}
