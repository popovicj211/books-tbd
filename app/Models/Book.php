<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
   // use HasFactory;


    protected $fillable = [
        'name' , 'published_year'
    ];

    public function authors(){
        return $this->belongsToMany(Author::class , 'books_authors' , 'book_id' , 'author_id' );
    }

    public function publishers(){
        return $this->belongsToMany(Publisher::class , 'books_publishers' , 'book_id' , 'pub_id');
    }


}
