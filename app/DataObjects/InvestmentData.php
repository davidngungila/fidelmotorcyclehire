<?php

declare(strict_types=1);

namespace App\DataObjects;

class InvestmentData
{
    public function __construct(
        public readonly ?string $product = null,
        public readonly ?float $amount_invested = null,
        public readonly ?float $units = null,
        public readonly ?float $current_value = null,
        public readonly ?float $profit_earned = null,
        public readonly ?float $return_rate = null,
        public readonly array $history = [],
        public readonly ?string $member_number = null,
    ) {
    }

    public static function fromArray(array $data): self
    {
        return new self(
            product: $data['product'] ?? $data['Product'] ?? null,
            amount_invested: isset($data['amount_invested']) ? (float) $data['amount_invested'] : (isset($data['AmountInvested']) ? (float) $data['AmountInvested'] : null),
            units: isset($data['units']) ? (float) $data['units'] : (isset($data['Units']) ? (float) $data['Units'] : null),
            current_value: isset($data['current_value']) ? (float) $data['current_value'] : (isset($data['CurrentValue']) ? (float) $data['CurrentValue'] : null),
            profit_earned: isset($data['profit_earned']) ? (float) $data['profit_earned'] : (isset($data['ProfitEarned']) ? (float) $data['ProfitEarned'] : null),
            return_rate: isset($data['return_rate']) ? (float) $data['return_rate'] : (isset($data['ReturnRate']) ? (float) $data['ReturnRate'] : null),
            history: $data['history'] ?? $data['History'] ?? [],
            member_number: $data['member_number'] ?? $data['MemberNumber'] ?? null,
        );
    }

    public function toArray(): array
    {
        return [
            'product' => $this->product,
            'amount_invested' => $this->amount_invested,
            'units' => $this->units,
            'current_value' => $this->current_value,
            'profit_earned' => $this->profit_earned,
            'return_rate' => $this->return_rate,
            'history' => $this->history,
            'member_number' => $this->member_number,
        ];
    }
}
