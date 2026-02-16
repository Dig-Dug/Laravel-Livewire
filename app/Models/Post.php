<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    
    use HasFactory;
    protected $fillable = [
        'title',
        'body',
        'image',
        'image_url',
    ];

    //optional
    public function excerpt(int $length = 140): string
    {
        return \Str::limit($this->body ?? '', $length);
    }

    public function getWordCountAttribute()
{
    return str_word_count(strip_tags($this->body));
}

public function getReadingTimeAttribute()
{
    $minutes = ceil($this->word_count / 200);
    return max(1, $minutes);
}
}
