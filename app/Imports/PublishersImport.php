<?php

namespace App\Imports;

use App\Models\Publisher;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class PublishersImport implements ToModel , WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new Publisher([
            "name" => $row['izdavac'],
            "created_at" => Carbon::now()->toDateTime()
        ]);
    }
}
