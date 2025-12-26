<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $guarded = ['id'];

    // Define relationship with Subcategory
    public function subcategories()
    {
        return $this->hasMany(Subcategory::class, 'category_id');
    }
}
