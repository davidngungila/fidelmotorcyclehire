<?php

declare(strict_types=1);

namespace App\DataObjects;

class SavingsData
{
    public function __construct(
        public readonly ?float $balance = null,
        public readonly ?float $interest_earned = null,
        public readonly ?float $running_balance = null,
        public readonly array $transactions = [],
        public readonly ?string $member_number = null,
    ) {
    }

    public static function fromArray(array $data): self
    {
        return new self(
            balance: isset($data['balance']) ? (float) $data['balance'] : (isset($data['Balance']) ? (float) $data['Balance'] : null),
            interest_earned: isset($data['interest_earned']) ? (float) $data['interest_earned'] : (isset($data['InterestEarned']) ? (float) $data['InterestEarned'] : null),
            running_balance: isset($data['running_balance']) ? (float) $data['running_balance'] : (isset($data['RunningBalance']) ? (float) $data['RunningBalance'] : null),
            transactions: $data['transactions'] ?? $data['Transactions'] ?? [],
            member_number: $data['member_number'] ?? $data['MemberNumber'] ?? null,
        );
    }

    public function toArray(): array
    {
        return [
            'balance' => $this->balance,
            'interest_earned' => $this->interest_earned,
            'running_balance' => $this->running_balance,
            'transactions' => $this->transactions,
            'member_number' => $this->member_number,
        ];
    }
}
