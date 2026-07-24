<?php

declare(strict_types=1);

namespace App\DataObjects;

class LoanData
{
    public function __construct(
        public readonly ?string $loan_number = null,
        public readonly ?string $loan_product = null,
        public readonly ?float $loan_amount = null,
        public readonly ?float $outstanding_balance = null,
        public readonly ?float $paid_amount = null,
        public readonly ?float $interest_rate = null,
        public readonly ?float $installment = null,
        public readonly ?string $status = null,
        public readonly ?string $disbursement_date = null,
        public readonly ?string $maturity_date = null,
        public readonly ?string $member_number = null,
    ) {
    }

    public static function fromArray(array $data): self
    {
        return new self(
            loan_number: $data['loan_number'] ?? $data['LoanNumber'] ?? null,
            loan_product: $data['loan_product'] ?? $data['LoanProduct'] ?? null,
            loan_amount: isset($data['loan_amount']) ? (float) $data['loan_amount'] : (isset($data['LoanAmount']) ? (float) $data['LoanAmount'] : null),
            outstanding_balance: isset($data['outstanding_balance']) ? (float) $data['outstanding_balance'] : (isset($data['OutstandingBalance']) ? (float) $data['OutstandingBalance'] : null),
            paid_amount: isset($data['paid_amount']) ? (float) $data['paid_amount'] : (isset($data['PaidAmount']) ? (float) $data['PaidAmount'] : null),
            interest_rate: isset($data['interest_rate']) ? (float) $data['interest_rate'] : (isset($data['InterestRate']) ? (float) $data['InterestRate'] : null),
            installment: isset($data['installment']) ? (float) $data['installment'] : (isset($data['Installment']) ? (float) $data['Installment'] : null),
            status: $data['status'] ?? $data['Status'] ?? null,
            disbursement_date: $data['disbursement_date'] ?? $data['DisbursementDate'] ?? null,
            maturity_date: $data['maturity_date'] ?? $data['MaturityDate'] ?? null,
            member_number: $data['member_number'] ?? $data['MemberNumber'] ?? null,
        );
    }

    public function toArray(): array
    {
        return [
            'loan_number' => $this->loan_number,
            'loan_product' => $this->loan_product,
            'loan_amount' => $this->loan_amount,
            'outstanding_balance' => $this->outstanding_balance,
            'paid_amount' => $this->paid_amount,
            'interest_rate' => $this->interest_rate,
            'installment' => $this->installment,
            'status' => $this->status,
            'disbursement_date' => $this->disbursement_date,
            'maturity_date' => $this->maturity_date,
            'member_number' => $this->member_number,
        ];
    }
}
