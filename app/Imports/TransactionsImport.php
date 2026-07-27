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
        // Log the row for debugging
        \Log::info('Processing row', ['row' => $row]);
        
        // Map Excel column names to database field names
        $membercode = $row['customerid'] ?? $row['membercode'] ?? null;
        $transactionType = $row['transactiontype'] ?? $row['transaction_type'] ?? null;
        $referenceNo = $row['referenceno'] ?? $row['reference_no'] ?? null;
        $date = $row['date'] ?? null;
        $amount = $row['amount'] ?? null;
        
        // Skip rows with missing required fields
        if (empty($date) || empty($membercode) || empty($transactionType) || empty($amount)) {
            \Log::warning('Skipping row - missing required fields', [
                'date' => $date,
                'membercode' => $membercode,
                'transaction_type' => $transactionType,
                'amount' => $amount
            ]);
            $this->skippedCount++;
            return null;
        }

        // Skip rows with formula values (starting with '=')
        if (is_string($date) && strpos($date, '=') === 0) {
            \Log::warning('Skipping row - formula in date', ['date' => $date]);
            $this->skippedCount++;
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
            \Log::warning('Skipping row - invalid date', ['date' => $date, 'error' => $e->getMessage()]);
            $this->skippedCount++;
            return null;
        }

        $this->importedCount++;
        
        \Log::info('Importing transaction', [
            'date' => $date,
            'membercode' => $membercode,
            'transaction_type' => $transactionType,
            'amount' => $amount
        ]);
        
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
