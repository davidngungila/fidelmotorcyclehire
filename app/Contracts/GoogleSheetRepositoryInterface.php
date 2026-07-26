<?php

declare(strict_types=1);

namespace App\Contracts;

interface GoogleSheetRepositoryInterface
{
    public function getSheetData(string $sheetName, ?string $range = null): array;

    public function getMemberByNumber(string $memberNumber): ?array;

    public function getAllMembers(): array;

    public function getMemberLoans(string $memberNumber): array;

    public function getMemberSavings(string $memberNumber): array;

    public function getMemberDeposits(string $memberNumber): array;

    public function getMemberSwf(string $memberNumber): array;

    public function getMemberInvestments(string $memberNumber): array;

    public function getMemberShares(string $memberNumber): array;

    public function getMemberTransactions(string $memberNumber): array;

    public function getMemberSavingPlans(string $memberNumber): array;

    public function getAllTransactions(): array;

    public function getAllSavingPlans(): array;

    public function getMemberStatements(string $memberNumber, string $type): array;

    public function getDashboardTotals(): array;

    public function searchMembers(string $query): array;

    public function addMember(array $memberData): bool;

    public function getLastSyncInfo(): array;
}
