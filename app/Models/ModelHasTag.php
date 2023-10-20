<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ModelHasTag extends Model
{
    use HasFactory;

    protected $fillable = ['tag_id'];

    public function tag()
    {
        return $this->belongsTo(Tag::class);
    }

    public function model()
    {
        return $this->morphTo();
    }
}