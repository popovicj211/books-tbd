<?php

namespace App\Imports;

use App\Models\Author;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class AuthorsImport implements ToModel, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new Author([
             "name" => $row['autor'],
            "created_at" => Carbon::now()->toDateTime()
        ]);
    }
}
