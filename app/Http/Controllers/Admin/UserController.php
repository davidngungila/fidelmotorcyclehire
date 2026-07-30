<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreBasicInfoRequest;
use App\Http\Requests\StoreContactInfoRequest;
use App\Http\Requests\StoreMembershipDetailsRequest;
use App\Http\Requests\StoreAccountInfoRequest;
use App\Http\Requests\StoreNextOfKinRequest;
use App\Http\Requests\StoreBankingInfoRequest;
use App\Http\Requests\StoreDocumentsInfoRequest;
use App\Http\Requests\StoreAdditionalInfoRequest;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\ActivityLog;
use App\Models\MemberProfile;
use App\Models\MemberType;
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

    public function storeBasicInfo(StoreBasicInfoRequest $request)
    {
        try {
            $validated = $request->validated();
            
            // Generate member number
            $memberNumber = 'MB' . date('ymd') . str_pad((string) rand(1, 9999), 4, '0', STR_PAD_LEFT);
            
            // Auto-generate password from last name (uppercase)
            $autoPassword = strtoupper($validated['last_name']);
            
            // Create user
            $user = User::create([
                'name' => trim($validated['first_name'] . ' ' . ($validated['middle_name'] ?? '') . ' ' . $validated['last_name']),
                'email' => $validated['email_address'] ?? null,
                'password' => Hash::make($autoPassword),
                'member_number' => $memberNumber,
                'member_type_id' => $validated['member_type_id'],
                'status' => $validated['status'],
            ]);

            // Create member profile
            MemberProfile::create([
                'user_id' => $user->id,
                'first_name' => $validated['first_name'],
                'middle_name' => $validated['middle_name'],
                'last_name' => $validated['last_name'],
                'gender' => $validated['gender'],
                'date_of_birth' => $validated['date_of_birth'],
                'national_id' => $validated['national_id'],
                'passport_driving_license' => $validated['passport_driving_license'],
                'registration_date' => $validated['registration_date'],
                'status' => $validated['status'],
            ]);

            ActivityLog::create([
                'user_id' => Auth::id(),
                'description' => 'Admin saved basic info for member: ' . $user->name,
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);

            $this->success('Basic information saved successfully.');
            return response()->json(['success' => true, 'user_id' => $user->id]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function storeContactInfo(StoreContactInfoRequest $request, $userId)
    {
        $validated = $request->validated();
        
        $profile = MemberProfile::where('user_id', $userId)->firstOrFail();
        $profile->update($validated);

        ActivityLog::create([
            'user_id' => Auth::id(),
            'description' => 'Admin saved contact info for member: ' . $profile->full_name,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        $this->success('Contact information saved successfully.');
        return response()->json(['success' => true]);
    }

    public function storeMembershipDetails(StoreMembershipDetailsRequest $request, $userId)
    {
        $validated = $request->validated();
        
        $profile = MemberProfile::where('user_id', $userId)->firstOrFail();
        $profile->update($validated);

        ActivityLog::create([
            'user_id' => Auth::id(),
            'description' => 'Admin saved membership details for member: ' . $profile->full_name,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        $this->success('Membership details saved successfully.');
        return response()->json(['success' => true]);
    }

    public function storeAccountInfo(StoreAccountInfoRequest $request, $userId)
    {
        $validated = $request->validated();
        
        $user = User::findOrFail($userId);
        
        if (!empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }
        
        if (!empty($validated['username'])) {
            $user->username = $validated['username'];
        }
        
        $user->role = $validated['role'];
        $user->email_verified_at = $validated['email_verified'] ?? false ? now() : null;
        $user->phone_verified_at = $validated['phone_verified'] ?? false ? now() : null;
        $user->save();

        ActivityLog::create([
            'user_id' => Auth::id(),
            'description' => 'Admin saved account info for member: ' . $user->name,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        $this->success('Account information saved successfully.');
        return response()->json(['success' => true]);
    }

    public function storeNextOfKin(StoreNextOfKinRequest $request, $userId)
    {
        $validated = $request->validated();
        
        $profile = MemberProfile::where('user_id', $userId)->firstOrFail();
        $profile->update([
            'kin_full_name' => $validated['kin_full_name'],
            'kin_relationship' => $validated['kin_relationship'],
            'kin_phone_number' => $validated['kin_phone_number'],
            'kin_address' => $validated['kin_address'],
        ]);

        ActivityLog::create([
            'user_id' => Auth::id(),
            'description' => 'Admin saved next of kin info for member: ' . $profile->full_name,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        $this->success('Next of kin information saved successfully.');
        return response()->json(['success' => true]);
    }

    public function storeBankingInfo(StoreBankingInfoRequest $request, $userId)
    {
        $validated = $request->validated();
        
        $profile = MemberProfile::where('user_id', $userId)->firstOrFail();
        $profile->update($validated);

        ActivityLog::create([
            'user_id' => Auth::id(),
            'description' => 'Admin saved banking info for member: ' . $profile->full_name,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        $this->success('Banking information saved successfully.');
        return response()->json(['success' => true]);
    }

    public function storeDocumentsInfo(StoreDocumentsInfoRequest $request, $userId)
    {
        $validated = $request->validated();
        
        $profile = MemberProfile::where('user_id', $userId)->firstOrFail();
        
        $updateData = [];
        
        if ($request->hasFile('passport_photo')) {
            $updateData['passport_photo'] = $request->file('passport_photo')->store('documents', 'public');
        }
        
        if ($request->hasFile('national_id_copy')) {
            $updateData['national_id_copy'] = $request->file('national_id_copy')->store('documents', 'public');
        }
        
        if ($request->hasFile('signature')) {
            $updateData['signature'] = $request->file('signature')->store('documents', 'public');
        }
        
        if (isset($validated['other_attachments'])) {
            $attachments = [];
            foreach ($validated['other_attachments'] as $file) {
                $attachments[] = $file->store('documents', 'public');
            }
            $updateData['other_attachments'] = $attachments;
        }
        
        $profile->update($updateData);

        ActivityLog::create([
            'user_id' => Auth::id(),
            'description' => 'Admin saved documents for member: ' . $profile->full_name,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        $this->success('Documents saved successfully.');
        return response()->json(['success' => true]);
    }

    public function storeAdditionalInfo(StoreAdditionalInfoRequest $request, $userId)
    {
        $validated = $request->validated();
        
        $profile = MemberProfile::where('user_id', $userId)->firstOrFail();
        $profile->update($validated);

        ActivityLog::create([
            'user_id' => Auth::id(),
            'description' => 'Admin saved additional info for member: ' . $profile->full_name,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        $this->success('Additional information saved successfully.');
        return response()->json(['success' => true]);
    }

    public function store(StoreUserRequest $request)
    {
        $validated = $request->validated();

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => $validated['role'],
            'member_number' => $validated['member_number'] ?? null,
            'member_type_id' => $validated['member_type_id'] ?? null,
            'status' => $request->input('status', 'active'),
        ]);

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
                'member_number' => $validated['member_number'] ?? null,
            ],
        ]);

        $this->success("User {$user->name} created successfully.");

        return redirect()->route('admin.users.index');
    }

    public function show(Request $request, string $encryptedId)
    {
        Gate::authorize('admin-only');

        try {
            $id = $this->encryptedIdService->decrypt($encryptedId);
        } catch (\Exception $e) {
            return redirect()->route('admin.users.index')
                ->with('error', 'Invalid user ID.');
        }

        $user = User::with(['roles', 'memberProfile', 'memberType'])->findOrFail($id);

        ActivityLog::create([
            'user_id' => Auth::id(),
            'subject_type' => 'user',
            'subject_id' => $user->id,
            'description' => "Admin viewed user details: {$user->name}",
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return view('admin.users.show', [
            'user' => $user,
            'encryptedId' => $encryptedId,
        ]);
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
