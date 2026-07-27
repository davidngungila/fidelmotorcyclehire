<?php

namespace App\Imports;

use App\Models\Transaction;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class TransactionsImport implements ToModel, WithHeadingRow
{
    private $importedCount = 0;
    private $skippedCount = 0;

    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        // Skip rows with missing required fields
        if (empty($row['date']) || empty($row['membercode']) || empty($row['transaction_type']) || empty($row['amount'])) {
            $this->skippedCount++;
            return null;
        }

        // Skip rows with formula values (starting with '=')
        if (is_string($row['date']) && strpos($row['date'], '=') === 0) {
            $this->skippedCount++;
            return null;
        }

        try {
            $date = \Carbon\Carbon::parse($row['date']);
        } catch (\Exception $e) {
            // Skip rows with unparseable dates
            $this->skippedCount++;
            return null;
        }

        $this->importedCount++;
        
        return new Transaction([
            'date' => $date,
            'membercode' => $row['membercode'],
            'transaction_type' => $row['transaction_type'],
            'reference_no' => $row['reference_no'] ?? null,
            'amount' => $row['amount'],
        ]);
    }

    public function getImportedCount()
    {
        return $this->importedCount;
    }

    public function getSkippedCount()
    {
        return $this->skippedCount;
    }
}
