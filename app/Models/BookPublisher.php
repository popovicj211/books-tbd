<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class BookPublisher extends Model
{
    protected $table= 'books_publishers';

    protected $fillable = [
         'book_id' , 'pub_id'
    ];
}
