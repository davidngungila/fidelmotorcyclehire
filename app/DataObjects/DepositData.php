<?php

declare(strict_types=1);

namespace App\DataObjects;

class DepositData
{
    public function __construct(
        public readonly ?string $certificate_number = null,
        public readonly ?string $product = null,
        public readonly ?float $amount = null,
        public readonly ?float $interest = null,
        public readonly ?string $start_date = null,
        public readonly ?string $maturity_date = null,
        public readonly ?string $status = null,
        public readonly ?float $current_value = null,
        public readonly ?string $member_number = null,
    ) {
    }

    public static function fromArray(array $data): self
    {
        return new self(
            certificate_number: $data['certificate_number'] ?? $data['CertificateNumber'] ?? null,
            product: $data['product'] ?? $data['Product'] ?? null,
            amount: isset($data['amount']) ? (float) $data['amount'] : (isset($data['Amount']) ? (float) $data['Amount'] : null),
            interest: isset($data['interest']) ? (float) $data['interest'] : (isset($data['Interest']) ? (float) $data['Interest'] : null),
            start_date: $data['start_date'] ?? $data['StartDate'] ?? null,
            maturity_date: $data['maturity_date'] ?? $data['MaturityDate'] ?? null,
            status: $data['status'] ?? $data['Status'] ?? null,
            current_value: isset($data['current_value']) ? (float) $data['current_value'] : (isset($data['CurrentValue']) ? (float) $data['CurrentValue'] : null),
            member_number: $data['member_number'] ?? $data['MemberNumber'] ?? null,
        );
    }

    public function toArray(): array
    {
        return [
            'certificate_number' => $this->certificate_number,
            'product' => $this->product,
            'amount' => $this->amount,
            'interest' => $this->interest,
            'start_date' => $this->start_date,
            'maturity_date' => $this->maturity_date,
            'status' => $this->status,
            'current_value' => $this->current_value,
            'member_number' => $this->member_number,
        ];
    }
}
