<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\ActivityLog;
use App\Models\BankingDetail;
use App\Models\MemberDocument;
use App\Models\MemberType;
use App\Models\NextOfKin;
use App\Models\Role;
use App\Models\User;
use App\Services\EncryptedIdService;
use App\Traits\FlashMessages;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    use FlashMessages;

    public function __construct(
        protected EncryptedIdService $encryptedIdService,
    ) {
    }

    public function index(Request $request)
    {
        $perPage = (int) $request->input('per_page', 15);
        $search = $request->input('q', '');

        $query = User::with('roles');

        if (! empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('member_number', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        $users = $query->latest()->paginate($perPage);

        if ($users instanceof LengthAwarePaginator) {
            $users->appends([
                'q' => $search,
                'per_page' => $perPage,
            ]);
        }

        ActivityLog::create([
            'user_id' => Auth::id(),
            'description' => 'Admin viewed users list',
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'properties' => [
                'search_query' => $search,
                'per_page' => $perPage,
                'total_count' => $users->total(),
            ],
        ]);

        return view('admin.users.index', [
            'users' => $users,
            'searchQuery' => $search,
            'perPage' => $perPage,
        ]);
    }

    public function create(Request $request)
    {
        ActivityLog::create([
            'user_id' => Auth::id(),
            'description' => 'Admin viewed create user form',
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        $roles = Role::all();
        $memberTypes = MemberType::active()->orderBy('priority', 'desc')->get();

        return view('admin.users.create', [
            'roles' => $roles,
            'memberTypes' => $memberTypes,
        ]);
    }

    public function store(StoreUserRequest $request)
    {
        $validated = $request->validated();

        // Generate member number if not provided
        if (empty($validated['member_number'])) {
            $validated['member_number'] = $this->generateMemberNumber();
        }

        // Set default registration date if not provided
        if (empty($validated['registration_date'])) {
            $validated['registration_date'] = now()->format('Y-m-d');
        }

        // Set default status if not provided
        if (empty($validated['status'])) {
            $validated['status'] = 'active';
        }

        // Combine name from first, middle, last names if provided
        if (!empty($validated['first_name']) || !empty($validated['last_name'])) {
            $validated['name'] = trim(($validated['first_name'] ?? '') . ' ' . ($validated['middle_name'] ?? '') . ' ' . ($validated['last_name'] ?? ''));
        }

        $user = User::create([
            'name' => $validated['name'],
            'first_name' => $validated['first_name'] ?? null,
            'middle_name' => $validated['middle_name'] ?? null,
            'last_name' => $validated['last_name'] ?? null,
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => $validated['role'],
            'member_number' => $validated['member_number'],
            'member_type_id' => $validated['member_type_id'] ?? null,
            'status' => $validated['status'],
            'phone' => $validated['phone'] ?? null,
            'alternative_phone' => $validated['alternative_phone'] ?? null,
            'gender' => $validated['gender'] ?? null,
            'date_of_birth' => $validated['date_of_birth'] ?? null,
            'national_id' => $validated['national_id'] ?? null,
            'passport_license' => $validated['passport_license'] ?? null,
            'registration_date' => $validated['registration_date'],
            'region' => $validated['region'] ?? null,
            'district' => $validated['district'] ?? null,
            'ward' => $validated['ward'] ?? null,
            'street_village' => $validated['street_village'] ?? null,
            'physical_address' => $validated['physical_address'] ?? null,
            'branch' => $validated['branch'] ?? null,
            'membership_category' => $validated['membership_category'] ?? null,
            'occupation' => $validated['occupation'] ?? null,
            'employer_business' => $validated['employer_business'] ?? null,
            'monthly_income' => $validated['monthly_income'] ?? null,
            'introduced_by' => $validated['introduced_by'] ?? null,
            'joining_fee' => $validated['joining_fee'] ?? null,
            'shares_purchased' => $validated['shares_purchased'] ?? null,
            'initial_savings' => $validated['initial_savings'] ?? null,
            'username' => $validated['username'] ?? $validated['member_number'],
            'email_verified' => $validated['email_verified'] ?? false,
            'phone_verified' => $validated['phone_verified'] ?? false,
            'notes' => $validated['notes'] ?? null,
            'tags' => $validated['tags'] ?? null,
            'custom_fields' => $validated['custom_fields'] ?? null,
        ]);

        // Save Next of Kin
        if (!empty($validated['next_of_kin_full_name'])) {
            NextOfKin::create([
                'user_id' => $user->id,
                'full_name' => $validated['next_of_kin_full_name'],
                'relationship' => $validated['next_of_kin_relationship'] ?? null,
                'phone_number' => $validated['next_of_kin_phone'] ?? null,
                'address' => $validated['next_of_kin_address'] ?? null,
            ]);
        }

        // Save Banking Details
        if (!empty($validated['bank_name']) || !empty($validated['mobile_money_network'])) {
            BankingDetail::create([
                'user_id' => $user->id,
                'bank_name' => $validated['bank_name'] ?? null,
                'bank_account_number' => $validated['bank_account_number'] ?? null,
                'account_name' => $validated['account_name'] ?? null,
                'mobile_money_network' => $validated['mobile_money_network'] ?? null,
                'mobile_wallet_number' => $validated['mobile_wallet_number'] ?? null,
            ]);
        }

        // Save Documents
        if (!empty($validated['passport_photo']) || !empty($validated['national_id_copy'])) {
            MemberDocument::create([
                'user_id' => $user->id,
                'passport_photo' => $validated['passport_photo'] ?? null,
                'national_id_copy' => $validated['national_id_copy'] ?? null,
                'signature' => $validated['signature'] ?? null,
                'other_attachments' => $validated['other_attachments'] ?? null,
            ]);
        }

        if (! empty($validated['role'])) {
            try {
                $user->assignRole($validated['role']);
            } catch (\Throwable $e) {
                $user->role = $validated['role'];
                $user->save();
            }
        }

        ActivityLog::create([
            'user_id' => Auth::id(),
            'subject_type' => 'user',
            'subject_id' => $user->id,
            'description' => "Admin created user: {$user->name}",
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'properties' => [
                'user_email' => $user->email,
                'user_role' => $validated['role'],
                'member_number' => $validated['member_number'],
            ],
        ]);

        $this->success("User {$user->name} created successfully.");

        return redirect()->route('admin.users.index');
    }

    protected function generateMemberNumber(): string
    {
        $date = now()->format('ymd');
        $prefix = 'MB' . $date;
        
        $lastMember = User::where('member_number', 'like', $prefix . '%')
            ->orderBy('member_number', 'desc')
            ->first();
        
        if ($lastMember) {
            $lastNumber = (int) substr($lastMember->member_number, -4);
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }
        
        return $prefix . str_pad((string) $newNumber, 4, '0', STR_PAD_LEFT);
    }

    public function edit(Request $request, string $encryptedId)
    {
        $id = (int) $this->encryptedIdService->decrypt($encryptedId);
        
        $user = User::with('roles')->findOrFail($id);

        ActivityLog::create([
            'user_id' => Auth::id(),
            'subject_type' => 'user',
            'subject_id' => $user->id,
            'description' => "Admin viewed edit user form: {$user->name}",
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        $roles = Role::all();

        return view('admin.users.edit', [
            'user' => $user,
            'roles' => $roles,
        ]);
    }

    public function update(UpdateUserRequest $request, string $encryptedId)
    {
        $id = (int) $this->encryptedIdService->decrypt($encryptedId);
        
        $user = User::findOrFail($id);
        $validated = $request->validated();

        $updateData = [
            'name' => $validated['name'],
            'email' => $validated['email'],
            'role' => $validated['role'],
            'status' => $request->input('status', $user->status ?? 'active'),
        ];

        if (! empty($validated['member_number'])) {
            $updateData['member_number'] = $validated['member_number'];
        }

        if (! empty($validated['password'])) {
            $updateData['password'] = Hash::make($validated['password']);
        }

        $user->update($updateData);

        if (! empty($validated['role'])) {
            try {
                $roleModel = Role::where('name', $validated['role'])->first();
                if ($roleModel) {
                    $user->roles()->sync([$roleModel->id]);
                }
            } catch (\Throwable $e) {
                $user->role = $validated['role'];
                $user->save();
            }
        }

        ActivityLog::create([
            'user_id' => Auth::id(),
            'subject_type' => 'user',
            'subject_id' => $user->id,
            'description' => "Admin updated user: {$user->name}",
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'properties' => [
                'user_email' => $user->email,
                'updated_fields' => array_keys($updateData),
            ],
        ]);

        $this->success("User {$user->name} updated successfully.");

        return redirect()->route('admin.users.index');
    }

    public function destroy(Request $request, string $encryptedId)
    {
        $id = (int) $this->encryptedIdService->decrypt($encryptedId);
        
        $user = User::findOrFail($id);
        $userName = $user->name;
        $userId = $user->id;

        try {
            $user->roles()->detach();
        } catch (\Throwable $e) {
        }

        if (method_exists($user, 'forceDelete')) {
            try {
                $user->forceDelete();
            } catch (\Throwable $e) {
                $user->delete();
            }
        } else {
            $user->delete();
        }

        ActivityLog::create([
            'user_id' => Auth::id(),
            'subject_type' => 'user',
            'subject_id' => $userId,
            'description' => "Admin deleted user: {$userName}",
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'properties' => [
                'user_name' => $userName,
                'deleted_by' => Auth::id(),
            ],
        ]);

        $this->success("User {$userName} deleted successfully.");

        return redirect()->route('admin.users.index');
    }

    public function resetPassword(Request $request, string $encryptedId)
    {
        try {
            $id = (int) $this->encryptedIdService->decrypt($encryptedId);
            
            $user = User::findOrFail($id);
            
            // Generate a random password
            $newPassword = strtoupper(substr(str_shuffle('ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789'), 0, 8));
            
            $user->password = Hash::make($newPassword);
            $user->save();

            ActivityLog::create([
                'user_id' => Auth::id(),
                'subject_type' => 'user',
                'subject_id' => $user->id,
                'description' => "Admin reset password for user: {$user->name}",
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'properties' => [
                    'user_email' => $user->email,
                    'reset_by' => Auth::id(),
                ],
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Password reset successfully',
                'new_password' => $newPassword,
                'user_name' => $user->name,
            ]);
        } catch (\Exception $e) {
            \Log::error('Password reset failed: ' . $e->getMessage(), [
                'exception' => $e,
                'encrypted_id' => $encryptedId,
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to reset password: ' . $e->getMessage(),
            ], 500);
        }
    }
}
