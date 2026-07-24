<?php

declare(strict_types=1);

namespace App\DataObjects;

class SwfData
{
    public function __construct(
        public readonly ?float $total_contribution = null,
        public readonly ?float $benefits = null,
        public readonly ?float $current_balance = null,
        public readonly array $contribution_history = [],
        public readonly ?string $member_number = null,
    ) {
    }

    public static function fromArray(array $data): self
    {
        return new self(
            total_contribution: isset($data['total_contribution']) ? (float) $data['total_contribution'] : (isset($data['TotalContribution']) ? (float) $data['TotalContribution'] : null),
            benefits: isset($data['benefits']) ? (float) $data['benefits'] : (isset($data['Benefits']) ? (float) $data['Benefits'] : null),
            current_balance: isset($data['current_balance']) ? (float) $data['current_balance'] : (isset($data['CurrentBalance']) ? (float) $data['CurrentBalance'] : null),
            contribution_history: $data['contribution_history'] ?? $data['ContributionHistory'] ?? [],
            member_number: $data['member_number'] ?? $data['MemberNumber'] ?? null,
        );
    }

    public function toArray(): array
    {
        return [
            'total_contribution' => $this->total_contribution,
            'benefits' => $this->benefits,
            'current_balance' => $this->current_balance,
            'contribution_history' => $this->contribution_history,
            'member_number' => $this->member_number,
        ];
    }
}
