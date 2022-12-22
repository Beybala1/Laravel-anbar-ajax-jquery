<?php

namespace App\Models;

use App\Models\Brand;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
{
    use HasFactory;

    protected $fillable = ['product','brand_id','buy','sell','count','logo'];

    public function brand(){
        return $this->belongsTo(Brand::class);
    }
    
}
