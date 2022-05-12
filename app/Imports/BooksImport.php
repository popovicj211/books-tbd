<?php

namespace App\Imports;

use App\Models\Book;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class BooksImport implements ToModel, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        $publishedYearArr = explode('/' , $row['godina_izdanja']);
        $publishedYearStr = $publishedYearArr[2].'-'.$publishedYearArr[1].'-'.$publishedYearArr[0];
        $publishedYearToTime = strtotime($publishedYearStr);
        return new Book([
            "name" => $row['naziv_knjige'],
            "published_year" => date('Y-m-d', $publishedYearToTime),
            "created_at" => Carbon::now()->toDateTime()
        ]);


    }
}




