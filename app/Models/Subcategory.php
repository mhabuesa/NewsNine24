<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subcategory extends Model
{
    protected $guarded = ['id'];


    public function categories()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }
}
