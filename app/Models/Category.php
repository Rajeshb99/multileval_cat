<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = ['title', 'cat_id'];

    public function children()
    {
        return $this->hasMany(Category::class, 'cat_id')->with('children');
    }
}
