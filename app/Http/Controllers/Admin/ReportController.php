<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Contracts\GoogleSheetRepositoryInterface;
use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Traits\FlashMessages;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ReportController extends Controller
{
    use FlashMessages;

    public function __construct(
        protected GoogleSheetRepositoryInterface $googleSheetRepository,
    ) {
    }

    public function index(Request $request)
    {
        $reports = [
            [
                'type' => 'members',
                'title' => 'Members Report',
                'description' => 'All registered members with personal details and status',
                'icon' => 'fa-id-card',
                'color' => 'from-primary-400 to-primary-600',
                'bg_color' => 'bg-primary-100 dark:bg-primary-900/40',
                'text_color' => 'text-primary-600 dark:text-primary-400',
            ],
            [
                'type' => 'loans',
                'title' => 'Loans Report',
                'description' => 'Loan applications, disbursements, balances and status',
                'icon' => 'fa-money-bill-wave',
                'color' => 'from-orange-400 to-orange-600',
                'bg_color' => 'bg-orange-100 dark:bg-orange-900/40',
                'text_color' => 'text-orange-600 dark:text-orange-400',
            ],
            [
                'type' => 'savings',
                'title' => 'Savings Report',
                'description' => 'Member savings accounts, deposits and balances',
                'icon' => 'fa-piggy-bank',
                'color' => 'from-blue-400 to-blue-600',
                'bg_color' => 'bg-blue-100 dark:bg-blue-900/40',
                'text_color' => 'text-blue-600 dark:text-blue-400',
            ],
            [
                'type' => 'deposits',
                'title' => 'Deposits Report',
                'description' => 'Fixed deposits, certificates and maturity details',
                'icon' => 'fa-file-invoice-dollar',
                'color' => 'from-purple-400 to-purple-600',
                'bg_color' => 'bg-purple-100 dark:bg-purple-900/40',
                'text_color' => 'text-purple-600 dark:text-purple-400',
            ],
            [
                'type' => 'swf',
                'title' => 'SWF Report',
                'description' => 'Social Welfare Fund contributions and benefits',
                'icon' => 'fa-shield-halved',
                'color' => 'from-pink-400 to-pink-600',
                'bg_color' => 'bg-pink-100 dark:bg-pink-900/40',
                'text_color' => 'text-pink-600 dark:text-pink-400',
            ],
            [
                'type' => 'investments',
                'title' => 'Investments Report',
                'description' => 'Investment portfolios, returns and performance',
                'icon' => 'fa-chart-line',
                'color' => 'from-lime-400 to-lime-600',
                'bg_color' => 'bg-lime-100 dark:bg-lime-900/40',
                'text_color' => 'text-lime-600 dark:text-lime-400',
            ],
            [
                'type' => 'combined',
                'title' => 'Combined Financial Report',
                'description' => 'Comprehensive summary of all financial activities',
                'icon' => 'fa-layer-group',
                'color' => 'from-indigo-400 to-indigo-600',
                'bg_color' => 'bg-indigo-100 dark:bg-indigo-900/40',
                'text_color' => 'text-indigo-600 dark:text-indigo-400',
            ],
        ];

        $filters = [
            'date_from' => $request->old('date_from', now()->subMonth()->format('Y-m-d')),
            'date_to' => $request->old('date_to', now()->format('Y-m-d')),
            'branch' => $request->old('branch', ''),
            'status' => $request->old('status', ''),
            'format' => $request->old('format', 'csv'),
        ];

        $branches = ['Dar es Salaam', 'Arusha', 'Mwanza', 'Dodoma', 'Mbeya', 'Tanga'];
        $statuses = ['Active', 'Pending', 'Closed', 'Defaulted', 'Matured'];

        ActivityLog::create([
            'user_id' => Auth::id(),
            'description' => 'Admin viewed reports dashboard',
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'properties' => [
                'reports_available' => count($reports),
            ],
        ]);

        return view('admin.reports.index', [
            'reports' => $reports,
            'filters' => $filters,
            'branches' => $branches,
            'statuses' => $statuses,
        ]);
    }

    public function generate(Request $request): StreamedResponse
    {
        $validated = $request->validate([
            'report_type' => ['required', 'in:members,loans,savings,deposits,swf,investments,combined'],
            'date_from' => ['nullable', 'date'],
            'date_to' => ['nullable', 'date'],
            'branch' => ['nullable', 'string'],
            'status' => ['nullable', 'string'],
            'format' => ['nullable', 'in:csv,print'],
        ]);

        $reportType = $validated['report_type'];
        $dateFrom = $validated['date_from'] ?? null;
        $dateTo = $validated['date_to'] ?? null;
        $branch = $validated['branch'] ?? null;
        $status = $validated['status'] ?? null;

        $data = $this->fetchReportData($reportType, $dateFrom, $dateTo, $branch, $status);
        $headers = $this->getReportHeaders($reportType);
        $rows = $this->formatReportRows($reportType, $data);
        $rowCount = count($rows);

        ActivityLog::create([
            'user_id' => Auth::id(),
            'description' => "Admin generated {$reportType} report",
            'subject_type' => 'report',
            'subject_id' => null,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'properties' => [
                'type' => $reportType,
                'filters' => [
                    'date_from' => $dateFrom,
                    'date_to' => $dateTo,
                    'branch' => $branch,
                    'status' => $status,
                ],
                'rows' => $rowCount,
            ],
        ]);

        $filename = 'report_' . $reportType . '_' . now()->format('Ymd_His') . '.csv';

        $response = new StreamedResponse(function () use ($headers, $rows) {
            $handle = fopen('php://output', 'w');

            if ($handle === false) {
                return;
            }

            fputcsv($handle, $headers);

            foreach ($rows as $row) {
                fputcsv($handle, $row);
            }

            fclose($handle);
        }, 200, [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            'Cache-Control' => 'no-cache, must-revalidate',
            'Pragma' => 'no-cache',
            'Expires' => 'Sat, 01 Jan 2000 00:00:00 GMT',
        ]);

        return $response;
    }

    protected function fetchReportData(string $type, ?string $dateFrom, ?string $dateTo, ?string $branch, ?string $status): array
    {
        return match ($type) {
            'members' => $this->googleSheetRepository->getAllMembers(),
            'loans' => $this->googleSheetRepository->getSheetData('Loans'),
            'savings' => $this->googleSheetRepository->getSheetData('Savings'),
            'deposits' => $this->googleSheetRepository->getSheetData('Deposits'),
            'swf' => $this->googleSheetRepository->getSheetData('SWF'),
            'investments' => $this->googleSheetRepository->getSheetData('Investments'),
            'combined' => $this->fetchCombinedData(),
            default => [],
        };
    }

    protected function fetchCombinedData(): array
    {
        return [
            'members' => $this->googleSheetRepository->getAllMembers(),
            'loans' => $this->googleSheetRepository->getSheetData('Loans'),
            'savings' => $this->googleSheetRepository->getSheetData('Savings'),
            'deposits' => $this->googleSheetRepository->getSheetData('Deposits'),
            'swf' => $this->googleSheetRepository->getSheetData('SWF'),
            'investments' => $this->googleSheetRepository->getSheetData('Investments'),
        ];
    }

    protected function getReportHeaders(string $type): array
    {
        return match ($type) {
            'members' => [
                'Member Number', 'Full Name', 'Gender', 'Phone', 'Email',
                'Branch', 'Occupation', 'Status', 'Registration Date',
            ],
            'loans' => [
                'Loan Number', 'Member Number', 'Member Name', 'Loan Type',
                'Principal Amount', 'Interest Rate', 'Term (Months)',
                'Monthly Installment', 'Total Payable', 'Balance',
                'Disbursement Date', 'Maturity Date', 'Status',
            ],
            'savings' => [
                'Member Number', 'Member Name', 'Account Number',
                'Opening Balance', 'Deposits Total', 'Withdrawals Total',
                'Current Balance', 'Interest Earned', 'Last Transaction Date', 'Status',
            ],
            'deposits' => [
                'Certificate Number', 'Member Number', 'Member Name',
                'Deposit Amount', 'Interest Rate', 'Term (Months)',
                'Maturity Amount', 'Start Date', 'Maturity Date', 'Status',
            ],
            'swf' => [
                'Member Number', 'Member Name', 'Monthly Contribution',
                'Total Contributions', 'Benefits Paid', 'Current Balance',
                'Last Contribution Date', 'Status',
            ],
            'investments' => [
                'Investment ID', 'Member Number', 'Member Name',
                'Investment Type', 'Invested Amount', 'Current Value',
                'Returns Earned', 'Investment Date', 'Maturity Date', 'Status',
            ],
            'combined' => [
                'Category', 'Record Count', 'Total Amount (TSh)', 'Active Count', 'Pending Count', 'Last Updated',
            ],
            default => ['Data'],
        };
    }

    protected function formatReportRows(string $type, array $data): array
    {
        if ($type === 'combined') {
            return $this->formatCombinedRows($data);
        }

        $rows = [];
        foreach ($data as $item) {
            $rows[] = match ($type) {
                'members' => [
                    $item['member_number'] ?? ($item['MemberNumber'] ?? ''),
                    $item['name'] ?? ($item['Name'] ?? ''),
                    $item['gender'] ?? ($item['Gender'] ?? ''),
                    $item['phone'] ?? ($item['Phone'] ?? ''),
                    $item['email'] ?? ($item['Email'] ?? ''),
                    $item['branch'] ?? ($item['Branch'] ?? ''),
                    $item['occupation'] ?? ($item['Occupation'] ?? ''),
                    $item['status'] ?? 'Active',
                    $item['registration_date'] ?? ($item['RegistrationDate'] ?? ''),
                ],
                'loans' => [
                    $item['loan_number'] ?? ($item['LoanNumber'] ?? ''),
                    $item['member_number'] ?? ($item['MemberNumber'] ?? ''),
                    $item['member_name'] ?? ($item['MemberName'] ?? ''),
                    $item['loan_type'] ?? ($item['LoanType'] ?? ''),
                    $item['principal'] ?? ($item['PrincipalAmount'] ?? 0),
                    $item['interest_rate'] ?? ($item['InterestRate'] ?? 0),
                    $item['term_months'] ?? ($item['TermMonths'] ?? 0),
                    $item['monthly_installment'] ?? ($item['MonthlyInstallment'] ?? 0),
                    $item['total_payable'] ?? ($item['TotalPayable'] ?? 0),
                    $item['balance'] ?? ($item['Balance'] ?? 0),
                    $item['disbursement_date'] ?? ($item['DisbursementDate'] ?? ''),
                    $item['maturity_date'] ?? ($item['MaturityDate'] ?? ''),
                    $item['status'] ?? ($item['Status'] ?? 'Pending'),
                ],
                'savings' => [
                    $item['member_number'] ?? ($item['MemberNumber'] ?? ''),
                    $item['member_name'] ?? ($item['MemberName'] ?? ''),
                    $item['account_number'] ?? ($item['AccountNumber'] ?? ''),
                    $item['opening_balance'] ?? ($item['OpeningBalance'] ?? 0),
                    $item['deposits_total'] ?? ($item['DepositsTotal'] ?? 0),
                    $item['withdrawals_total'] ?? ($item['WithdrawalsTotal'] ?? 0),
                    $item['current_balance'] ?? ($item['CurrentBalance'] ?? 0),
                    $item['interest_earned'] ?? ($item['InterestEarned'] ?? 0),
                    $item['last_transaction_date'] ?? ($item['LastTransactionDate'] ?? ''),
                    $item['status'] ?? ($item['Status'] ?? 'Active'),
                ],
                'deposits' => [
                    $item['certificate_number'] ?? ($item['CertificateNumber'] ?? ''),
                    $item['member_number'] ?? ($item['MemberNumber'] ?? ''),
                    $item['member_name'] ?? ($item['MemberName'] ?? ''),
                    $item['deposit_amount'] ?? ($item['DepositAmount'] ?? 0),
                    $item['interest_rate'] ?? ($item['InterestRate'] ?? 0),
                    $item['term_months'] ?? ($item['TermMonths'] ?? 0),
                    $item['maturity_amount'] ?? ($item['MaturityAmount'] ?? 0),
                    $item['start_date'] ?? ($item['StartDate'] ?? ''),
                    $item['maturity_date'] ?? ($item['MaturityDate'] ?? ''),
                    $item['status'] ?? ($item['Status'] ?? 'Active'),
                ],
                'swf' => [
                    $item['member_number'] ?? ($item['MemberNumber'] ?? ''),
                    $item['member_name'] ?? ($item['MemberName'] ?? ''),
                    $item['monthly_contribution'] ?? ($item['MonthlyContribution'] ?? 0),
                    $item['total_contributions'] ?? ($item['TotalContributions'] ?? 0),
                    $item['benefits_paid'] ?? ($item['BenefitsPaid'] ?? 0),
                    $item['current_balance'] ?? ($item['CurrentBalance'] ?? 0),
                    $item['last_contribution_date'] ?? ($item['LastContributionDate'] ?? ''),
                    $item['status'] ?? ($item['Status'] ?? 'Active'),
                ],
                'investments' => [
                    $item['investment_id'] ?? ($item['InvestmentID'] ?? ''),
                    $item['member_number'] ?? ($item['MemberNumber'] ?? ''),
                    $item['member_name'] ?? ($item['MemberName'] ?? ''),
                    $item['investment_type'] ?? ($item['InvestmentType'] ?? ''),
                    $item['invested_amount'] ?? ($item['InvestedAmount'] ?? 0),
                    $item['current_value'] ?? ($item['CurrentValue'] ?? 0),
                    $item['returns_earned'] ?? ($item['ReturnsEarned'] ?? 0),
                    $item['investment_date'] ?? ($item['InvestmentDate'] ?? ''),
                    $item['maturity_date'] ?? ($item['MaturityDate'] ?? ''),
                    $item['status'] ?? ($item['Status'] ?? 'Active'),
                ],
                default => [json_encode($item)],
            };
        }

        return $rows;
    }

    protected function formatCombinedRows(array $data): array
    {
        $rows = [];
        $categories = [
            'members' => ['label' => 'Members', 'amount_field' => null],
            'loans' => ['label' => 'Loans', 'amount_field' => 'principal'],
            'savings' => ['label' => 'Savings', 'amount_field' => 'current_balance'],
            'deposits' => ['label' => 'Deposits', 'amount_field' => 'deposit_amount'],
            'swf' => ['label' => 'SWF', 'amount_field' => 'current_balance'],
            'investments' => ['label' => 'Investments', 'amount_field' => 'invested_amount'],
        ];

        foreach ($categories as $key => $meta) {
            $items = $data[$key] ?? [];
            $count = count($items);
            $totalAmount = 0;
            $activeCount = 0;
            $pendingCount = 0;

            foreach ($items as $item) {
                if ($meta['amount_field']) {
                    $totalAmount += (float) ($item[$meta['amount_field']] ?? ($item[ucfirst($meta['amount_field'])] ?? 0));
                }
                $status = strtolower((string) ($item['status'] ?? ($item['Status'] ?? 'active')));
                if ($status === 'active') {
                    $activeCount++;
                } elseif ($status === 'pending') {
                    $pendingCount++;
                }
            }

            $rows[] = [
                $meta['label'],
                $count,
                number_format($totalAmount, 2),
                $activeCount,
                $pendingCount,
                now()->format('Y-m-d H:i:s'),
            ];
        }

        return $rows;
    }
}
