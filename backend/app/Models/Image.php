<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    use HasFactory;

    protected $fillable = ['book_id', 'name', 'path'];

    public function book() {
        return $this->belongsTo(Book::class);
    }
}
