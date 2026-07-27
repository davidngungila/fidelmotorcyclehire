<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CustomerProfile;
use App\Models\SavingBalance;
use App\Models\SavingPlan;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class GoogleSheetsReceiverController extends Controller
{
    public function syncFullData(Request $request)
    {
        DB::beginTransaction();

        try {
            $results = [
                'customers' => 0,
                'saving_plans' => 0,
                'saving_balances' => 0,
                'transactions' => 0
            ];

            // Process Customers
            if ($request->has('customers')) {
                foreach ($request->customers as $data) {
                    CustomerProfile::updateOrCreate(
                        ['customer_id' => $data['customer_id']],
                        [
                            'customer_name' => $data['customer_name'] ?? '',
                            'email_address' => $data['email_address'] ?? null,
                            'phone_number' => $data['phone_number'] ?? null,
                            'member_type' => $data['member_type'] ?? 'Full',
                            'start_date' => $data['start_date'] ?? null,
                            'end_date' => $data['end_date'] ?? null,
                            'account_status' => $data['account_status'] ?? 'Active'
                        ]
                    );
                    $results['customers']++;
                }
            }

            // Process Saving Plans
            if ($request->has('saving_plans')) {
                foreach ($request->saving_plans as $data) {
                    SavingPlan::updateOrCreate(
                        ['memberid' => $data['memberid']],
                        [
                            'name' => $data['name'] ?? '',
                            'membership' => $data['membership'] ?? null,
                            'monthly_goal' => $data['monthly_goal'] ?? 0,
                            'goal' => $data['goal'] ?? 0
                        ]
                    );
                    $results['saving_plans']++;
                }
            }

            // Process Saving Balances
            if ($request->has('saving_balances')) {
                foreach ($request->saving_balances as $data) {
                    SavingBalance::updateOrCreate(
                        ['customer_id' => $data['customer_id']],
                        [
                            'monthly_saving_target' => $data['monthly_saving_target'] ?? 0,
                            'monthly_total_savings_deposits' => $data['monthly_total_savings_deposits'] ?? 0,
                            'monthly_goal_achievement' => $data['monthly_goal_achievement'] ?? 0,
                            'overall_saving_goal' => $data['overall_saving_goal'] ?? 0,
                            'total_saved' => $data['total_saved'] ?? 0,
                            'overall_goal_achievement' => $data['overall_goal_achievement'] ?? 0,
                            'flexi_opening_balance' => $data['flexi_opening_balance'] ?? 0,
                            'flexi_deposit' => $data['flexi_deposit'] ?? 0,
                            'flexi_withdrawal' => $data['flexi_withdrawal'] ?? 0,
                            'flexi_balance' => $data['flexi_balance'] ?? 0,
                            'rda_opening_balance' => $data['rda_opening_balance'] ?? 0,
                            'rda_deposit' => $data['rda_deposit'] ?? 0,
                            'rda_withdrawal' => $data['rda_withdrawal'] ?? 0,
                            'rda_balance' => $data['rda_balance'] ?? 0,
                            'emergency_opening_balance' => $data['emergency_opening_balance'] ?? 0,
                            'emergency_deposit' => $data['emergency_deposit'] ?? 0,
                            'emergency_withdrawal' => $data['emergency_withdrawal'] ?? 0,
                            'emergency_balance' => $data['emergency_balance'] ?? 0,
                            'business_opening_balance' => $data['business_opening_balance'] ?? 0,
                            'business_deposit' => $data['business_deposit'] ?? 0,
                            'business_withdrawal' => $data['business_withdrawal'] ?? 0,
                            'business_balance' => $data['business_balance'] ?? 0,
                            'total_balance' => $data['total_balance'] ?? 0,
                            'interest_payable' => $data['interest_payable'] ?? 0,
                            'savings_held_for_loan_security' => $data['savings_held_for_loan_security'] ?? 0,
                            'free_savings_emergency' => $data['free_savings_emergency'] ?? 0,
                            'free_savings_rda_flexi_business' => $data['free_savings_rda_flexi_business'] ?? 0,
                            'total_free_saving' => $data['total_free_saving'] ?? 0,
                            'premature_withdraw_charge' => $data['premature_withdraw_charge'] ?? 0
                        ]
                    );
                    $results['saving_balances']++;
                }
            }

            // Process Transactions
            if ($request->has('transactions')) {
                foreach ($request->transactions as $data) {
                    Transaction::create([
                        'membercode' => $data['customer_id'],
                        'date' => $data['transaction_date'],
                        'transaction_type' => $data['transaction_type'] ?? '',
                        'reference_no' => $data['reference_no'] ?? null,
                        'amount' => $data['amount'] ?? 0
                    ]);
                    $results['transactions']++;
                }
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Data synced successfully',
                'results' => $results,
                'total' => array_sum($results)
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Sync error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function syncCustomers(Request $request)
    {
        DB::beginTransaction();

        try {
            $count = 0;

            if ($request->has('customers')) {
                foreach ($request->customers as $data) {
                    CustomerProfile::updateOrCreate(
                        ['customer_id' => $data['customer_id']],
                        [
                            'customer_name' => $data['customer_name'] ?? '',
                            'email_address' => $data['email_address'] ?? null,
                            'phone_number' => $data['phone_number'] ?? null,
                            'member_type' => $data['member_type'] ?? 'Full',
                            'start_date' => $data['start_date'] ?? null,
                            'end_date' => $data['end_date'] ?? null,
                            'account_status' => $data['account_status'] ?? 'Active'
                        ]
                    );
                    $count++;
                }
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Customers synced successfully',
                'total' => $count
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Customers sync error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function syncBalances(Request $request)
    {
        DB::beginTransaction();

        try {
            $count = 0;

            if ($request->has('balances')) {
                foreach ($request->balances as $data) {
                    SavingBalance::updateOrCreate(
                        ['customer_id' => $data['customer_id']],
                        [
                            'monthly_saving_target' => $data['monthly_saving_target'] ?? 0,
                            'monthly_total_savings_deposits' => $data['monthly_total_savings_deposits'] ?? 0,
                            'monthly_goal_achievement' => $data['monthly_goal_achievement'] ?? 0,
                            'overall_saving_goal' => $data['overall_saving_goal'] ?? 0,
                            'total_saved' => $data['total_saved'] ?? 0,
                            'overall_goal_achievement' => $data['overall_goal_achievement'] ?? 0,
                            'flexi_opening_balance' => $data['flexi_opening_balance'] ?? 0,
                            'flexi_deposit' => $data['flexi_deposit'] ?? 0,
                            'flexi_withdrawal' => $data['flexi_withdrawal'] ?? 0,
                            'flexi_balance' => $data['flexi_balance'] ?? 0,
                            'rda_opening_balance' => $data['rda_opening_balance'] ?? 0,
                            'rda_deposit' => $data['rda_deposit'] ?? 0,
                            'rda_withdrawal' => $data['rda_withdrawal'] ?? 0,
                            'rda_balance' => $data['rda_balance'] ?? 0,
                            'emergency_opening_balance' => $data['emergency_opening_balance'] ?? 0,
                            'emergency_deposit' => $data['emergency_deposit'] ?? 0,
                            'emergency_withdrawal' => $data['emergency_withdrawal'] ?? 0,
                            'emergency_balance' => $data['emergency_balance'] ?? 0,
                            'business_opening_balance' => $data['business_opening_balance'] ?? 0,
                            'business_deposit' => $data['business_deposit'] ?? 0,
                            'business_withdrawal' => $data['business_withdrawal'] ?? 0,
                            'business_balance' => $data['business_balance'] ?? 0,
                            'total_balance' => $data['total_balance'] ?? 0,
                            'interest_payable' => $data['interest_payable'] ?? 0,
                            'savings_held_for_loan_security' => $data['savings_held_for_loan_security'] ?? 0,
                            'free_savings_emergency' => $data['free_savings_emergency'] ?? 0,
                            'free_savings_rda_flexi_business' => $data['free_savings_rda_flexi_business'] ?? 0,
                            'total_free_saving' => $data['total_free_saving'] ?? 0,
                            'premature_withdraw_charge' => $data['premature_withdraw_charge'] ?? 0
                        ]
                    );
                    $count++;
                }
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Balances synced successfully',
                'total' => $count
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Balances sync error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function syncTransactions(Request $request)
    {
        DB::beginTransaction();

        try {
            $count = 0;

            if ($request->has('transactions')) {
                foreach ($request->transactions as $data) {
                    Transaction::create([
                        'membercode' => $data['customer_id'],
                        'date' => $data['transaction_date'],
                        'transaction_type' => $data['transaction_type'] ?? '',
                        'reference_no' => $data['reference_no'] ?? null,
                        'amount' => $data['amount'] ?? 0
                    ]);
                    $count++;
                }
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Transactions synced successfully',
                'total' => $count
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Transactions sync error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function syncSavingPlans(Request $request)
    {
        DB::beginTransaction();

        try {
            $count = 0;

            if ($request->has('saving_plans')) {
                foreach ($request->saving_plans as $data) {
                    SavingPlan::updateOrCreate(
                        ['memberid' => $data['memberid']],
                        [
                            'name' => $data['name'] ?? '',
                            'membership' => $data['membership'] ?? null,
                            'monthly_goal' => $data['monthly_goal'] ?? 0,
                            'goal' => $data['goal'] ?? 0
                        ]
                    );
                    $count++;
                }
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Saving plans synced successfully',
                'total' => $count
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Saving plans sync error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
