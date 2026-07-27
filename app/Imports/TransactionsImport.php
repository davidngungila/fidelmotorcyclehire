<?php

namespace App\Imports;

use App\Models\Transaction;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;

class TransactionsImport implements ToModel, WithHeadingRow, WithBatchInserts, WithChunkReading
{
    private $importedCount = 0;
    private $skippedCount = 0;

    public function batchSize(): int
    {
        return 500;
    }

    public function chunkSize(): int
    {
        return 500;
    }

    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        // Skip completely empty rows early to save memory
        if (empty(array_filter($row, function($value) {
            return $value !== null && $value !== '';
        }))) {
            return null;
        }
        
        // Map Excel column names to database field names
        $membercode = $row['customerid'] ?? $row['membercode'] ?? null;
        $transactionType = $row['transactiontype'] ?? $row['transaction_type'] ?? null;
        $referenceNo = $row['referenceno'] ?? $row['reference_no'] ?? null;
        $date = $row['date'] ?? null;
        $amount = $row['amount'] ?? null;
        
        // Skip rows with missing required fields
        if (empty($date) || empty($membercode) || empty($transactionType) || empty($amount)) {
            return null;
        }

        // Skip rows with formula values (starting with '=')
        if (is_string($date) && strpos($date, '=') === 0) {
            return null;
        }

        try {
            // Handle Excel serial date format (numbers like 46229)
            if (is_numeric($date)) {
                $date = \Carbon\Carbon::createFromFormat('Y-m-d', \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($date)->format('Y-m-d'));
            } else {
                $date = \Carbon\Carbon::parse($date);
            }
        } catch (\Exception $e) {
            // Skip rows with unparseable dates
            return null;
        }

        $this->importedCount++;
        
        return new Transaction([
            'date' => $date,
            'membercode' => $membercode,
            'transaction_type' => $transactionType,
            'reference_no' => $referenceNo,
            'amount' => $amount,
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
