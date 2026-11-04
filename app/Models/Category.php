<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    // use HasFactory;

    // Champs que l'on peut remplir en masse (sécurité Laravel)
    protected $fillable = ['name', 'slug'];

    // Relation : une catégorie a plusieurs cours
    public function courses()
    {
        return $this->hasMany(Course::class);
    }
}
