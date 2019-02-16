<?php

namespace App\Models;

use App\Models\Traits\HasScopes;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasScopes;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'price',
    ];

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class);
    }

    public function variations()
    {
        return $this->hasMany(ProductVariation::class)->orderBy('order', 'asc');
    }
}
