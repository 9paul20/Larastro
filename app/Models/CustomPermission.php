<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Permission\Models\Permission as SpatiePermission;

class CustomPermission extends SpatiePermission
{
    use HasFactory;

    protected $guarded = [];

    public function tags()
    {
        return $this->morphToMany(Tag::class, 'model', 'model_has_tags', 'model_id', 'tag_id');
    }
}