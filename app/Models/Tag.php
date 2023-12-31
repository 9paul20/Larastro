<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $fillable = ['id', 'name', 'description'];

    public function taggable()
    {
        return $this->morphTo();
    }
}