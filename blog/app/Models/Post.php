<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Post extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = ['title','description'];
    // protected $guarded = [];

    public function comment()
    {
        return $this->hasMany(Comment::class, 'post_id', 'id');
    }

    public function groups()
    {
        return $this->belongsToMany(Group::class);
    }
}
