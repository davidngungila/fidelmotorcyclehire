<?php

namespace App\Exports;

use App\Models\Transaction;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class TransactionsExport implements FromCollection, WithHeadings, WithMapping
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Transaction::orderBy('date', 'desc')->get();
    }

    public function headings(): array
    {
        return [
            'Date',
            'Member Code',
            'Transaction Type',
            'Reference No',
            'Amount',
        ];
    }

    public function map($transaction): array
    {
        return [
            $transaction->date->format('Y-m-d'),
            $transaction->membercode,
            $transaction->transaction_type,
            $transaction->reference_no,
            $transaction->amount,
        ];
    }
}
