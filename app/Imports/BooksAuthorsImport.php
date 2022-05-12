<?php

namespace App\Imports;

use App\Models\Author;
use App\Models\Book;
use App\Models\BookAuthor;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class BooksAuthorsImport implements ToModel, WithHeadingRow
{
    private $books;
    private $authors;

    public function __construct($books, $authors)
    {

      $this->books = $books;
      $this->authors = $authors;
    }

    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
      //  $this->books = Book::where('name', '=' ,  $row['naziv_knjige'])->first()->id;
    //    $this->authors = Author::where('name', '=' , $row['autor'])->first()->id;
        $bookId =  $this->books::where('name', '=' ,  $row['naziv_knjige'])->first()->id;
         $authorId = $this->authors::where('name', '=' , $row['autor'])->first()->id;

        return new BookAuthor([
               "book_id" =>  $bookId,
               "author_id" => $authorId,
                 "created_at" => Carbon::now()->toDateTime()
        ]);
    }
}
