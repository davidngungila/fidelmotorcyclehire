<?php

namespace App\Imports;

use App\Contracts\GoogleSheetRepositoryInterface;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class MembersImport implements ToCollection, WithHeadingRow
{
    protected $googleSheetRepository;
    protected $importedCount = 0;
    protected $errors = [];

    public function __construct(GoogleSheetRepositoryInterface $googleSheetRepository)
    {
        $this->googleSheetRepository = $googleSheetRepository;
    }

    public function collection(Collection $collection)
    {
        foreach ($collection as $row) {
            try {
                $memberData = [
                    'member_number' => $row['member_number'] ?? $row['MemberNumber'] ?? null,
                    'name' => $row['name'] ?? $row['Name'] ?? null,
                    'gender' => $row['gender'] ?? $row['Gender'] ?? null,
                    'phone' => $row['phone'] ?? $row['Phone'] ?? null,
                    'email' => $row['email'] ?? $row['Email'] ?? null,
                    'branch' => $row['branch'] ?? $row['Branch'] ?? null,
                    'status' => $row['status'] ?? $row['Status'] ?? 'Active',
                    'join_date' => $row['join_date'] ?? $row['JoinDate'] ?? now()->format('Y-m-d'),
                ];

                if (empty($memberData['member_number']) || empty($memberData['name'])) {
                    $this->errors[] = "Row skipped: Missing member number or name";
                    continue;
                }

                $this->googleSheetRepository->addMember($memberData);
                $this->importedCount++;
            } catch (\Exception $e) {
                $this->errors[] = "Row error: " . $e->getMessage();
            }
        }
    }

    public function getImportedCount(): int
    {
        return $this->importedCount;
    }

    public function getErrors(): array
    {
        return $this->errors;
    }
}
