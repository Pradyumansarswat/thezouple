<?php

namespace App\Imports;

use App\Pincode;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class pinImport implements ToModel,WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        if (empty($row['pincode'])) {
            return null;
        }

        $pincode = preg_replace('/\D/', '', (string) $row['pincode']);

        if (strlen($pincode) !== 6) {
            return null;
        }

        return Pincode::updateOrCreate(
            ['pincode' => $pincode],
            [
                'city' => isset($row['city']) ? trim((string) $row['city']) : null,
                'state' => isset($row['state']) ? trim((string) $row['state']) : null,
            ]
        );
    }
}
