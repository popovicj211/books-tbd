<?php

namespace App\Imports;

use App\Models\Book;
use App\Models\BookPublisher;
use App\Models\Publisher;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class BooksPublishersImport implements ToModel, WithHeadingRow
{
    private $books;
    private $publishers;
    public function __construct($books, $publishers)
    {
        $this->books = $books;
        $this->publishers = $publishers;
    }


    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
      //  $this->books = Book::where('name', '=' ,  $row['naziv_knjige'])->first()->id;
     //   $this->publishers = Publisher::where('name', '=' ,  $row['izdavac'])->first()->id;
       $bookId = $this->books::where('name', '=' ,  $row['naziv_knjige'])->first()->id;
       $pubId = $this->publishers::where('name', '=' ,  $row['izdavac'])->first()->id;
        return new BookPublisher([
            "book_id" => $bookId,
            "pub_id" =>  $pubId,
            "created_at" => Carbon::now()->toDateTime()
        ]);
    }
}
