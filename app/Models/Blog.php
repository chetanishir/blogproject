<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Blog extends Model
{
    use HasFactory;
    protected $fillable = [
        'title', 
        'image', 
        'email', 
        'content', 
        'category',
        'date', 
        'author_name',
        'author_id'
        
    ];
    public function user()
    {
        return $this->belongsTo(User::class, 'author_id');
    }
}
