<?php

namespace App\Imports;

use App\Models\Transaction;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class TransactionsImport implements ToModel, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new Transaction([
            'date' => \Carbon\Carbon::parse($row['date']),
            'membercode' => $row['membercode'],
            'transaction_type' => $row['transaction_type'],
            'reference_no' => $row['reference_no'] ?? null,
            'amount' => $row['amount'],
        ]);
    }
}
