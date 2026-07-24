<?php

declare(strict_types=1);

namespace App\DataObjects;

class DashboardTotals
{
    public function __construct(
        public readonly ?int $total_members = null,
        public readonly ?float $total_savings = null,
        public readonly ?float $total_loans = null,
        public readonly ?float $total_deposits = null,
        public readonly ?float $total_investments = null,
        public readonly ?float $total_swf = null,
    ) {
    }

    public static function fromArray(array $data): self
    {
        return new self(
            total_members: isset($data['total_members']) ? (int) $data['total_members'] : (isset($data['TotalMembers']) ? (int) $data['TotalMembers'] : null),
            total_savings: isset($data['total_savings']) ? (float) $data['total_savings'] : (isset($data['TotalSavings']) ? (float) $data['TotalSavings'] : null),
            total_loans: isset($data['total_loans']) ? (float) $data['total_loans'] : (isset($data['TotalLoans']) ? (float) $data['TotalLoans'] : null),
            total_deposits: isset($data['total_deposits']) ? (float) $data['total_deposits'] : (isset($data['TotalDeposits']) ? (float) $data['TotalDeposits'] : null),
            total_investments: isset($data['total_investments']) ? (float) $data['total_investments'] : (isset($data['TotalInvestments']) ? (float) $data['TotalInvestments'] : null),
            total_swf: isset($data['total_swf']) ? (float) $data['total_swf'] : (isset($data['TotalSwf']) ? (float) $data['TotalSwf'] : null),
        );
    }

    public function toArray(): array
    {
        return [
            'total_members' => $this->total_members,
            'total_savings' => $this->total_savings,
            'total_loans' => $this->total_loans,
            'total_deposits' => $this->total_deposits,
            'total_investments' => $this->total_investments,
            'total_swf' => $this->total_swf,
        ];
    }
}
