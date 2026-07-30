<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\MemberType;
use App\Services\EncryptedIdService;
use App\Traits\FlashMessages;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class MemberTypeController extends Controller
{
    use FlashMessages;

    public function __construct(
        protected EncryptedIdService $encryptedIdService,
    ) {
    }

    public function index(Request $request): View
    {
        Gate::authorize('admin-only');

        $searchQuery = $request->input('q', '');
        $statusFilter = $request->input('status', '');

        $query = MemberType::query();

        if (!empty($searchQuery)) {
            $query->where(function ($q) use ($searchQuery) {
                $q->where('name', 'like', "%{$searchQuery}%")
                    ->orWhere('code', 'like', "%{$searchQuery}%")
                    ->orWhere('description', 'like', "%{$searchQuery}%");
            });
        }

        if (!empty($statusFilter)) {
            $query->where('status', $statusFilter);
        }

        $memberTypes = $query->orderBy('priority', 'desc')->orderBy('name')->paginate(15);

        $activeCount = MemberType::where('status', 'active')->count();
        $inactiveCount = MemberType::where('status', 'inactive')->count();

        ActivityLog::create([
            'user_id' => Auth::id(),
            'description' => 'Admin viewed member types list',
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return view('admin.member-types.index', [
            'memberTypes' => $memberTypes,
            'searchQuery' => $searchQuery,
            'statusFilter' => $statusFilter,
            'activeCount' => $activeCount,
            'inactiveCount' => $inactiveCount,
        ]);
    }

    public function create(Request $request): View
    {
        Gate::authorize('admin-only');

        return view('admin.member-types.create');
    }

    public function store(Request $request)
    {
        Gate::authorize('admin-only');

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:member_types,code',
            'description' => 'nullable|string',
            'registration_fee' => 'required|numeric|min:0',
            'monthly_contribution' => 'required|numeric|min:0',
            'min_savings' => 'required|numeric|min:0',
            'max_loan_multiplier' => 'required|integer|min:1',
            'interest_rate_discount' => 'required|numeric|min:0|max:100',
            'can_vote' => 'required|boolean',
            'can_hold_office' => 'required|boolean',
            'priority' => 'required|integer|min:0',
            'status' => 'required|in:active,inactive',
        ]);

        MemberType::create($validated);

        ActivityLog::create([
            'user_id' => Auth::id(),
            'description' => 'Admin created member type: ' . $validated['name'],
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        $this->success('Member type created successfully.');

        return redirect()->route('admin.member-types.index');
    }

    public function show(Request $request, string $encryptedId): View
    {
        Gate::authorize('admin-only');

        try {
            $id = $this->encryptedIdService->decrypt($encryptedId);
        } catch (\Exception $e) {
            return redirect()->route('admin.member-types.index')
                ->with('error', 'Invalid member type ID.');
        }

        $memberType = MemberType::findOrFail($id);
        $membersCount = $memberType->users()->count();

        ActivityLog::create([
            'user_id' => Auth::id(),
            'description' => 'Admin viewed member type details: ' . $memberType->name,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return view('admin.member-types.show', compact('memberType', 'encryptedId', 'membersCount'));
    }

    public function edit(Request $request, string $encryptedId): View
    {
        Gate::authorize('admin-only');

        try {
            $id = $this->encryptedIdService->decrypt($encryptedId);
        } catch (\Exception $e) {
            return redirect()->route('admin.member-types.index')
                ->with('error', 'Invalid member type ID.');
        }

        $memberType = MemberType::findOrFail($id);

        ActivityLog::create([
            'user_id' => Auth::id(),
            'description' => 'Admin viewed edit member type form: ' . $memberType->name,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return view('admin.member-types.edit', compact('memberType', 'encryptedId'));
    }

    public function update(Request $request, string $encryptedId)
    {
        Gate::authorize('admin-only');

        try {
            $id = $this->encryptedIdService->decrypt($encryptedId);
        } catch (\Exception $e) {
            return redirect()->route('admin.member-types.index')
                ->with('error', 'Invalid member type ID.');
        }

        $memberType = MemberType::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:member_types,code,' . $id,
            'description' => 'nullable|string',
            'registration_fee' => 'required|numeric|min:0',
            'monthly_contribution' => 'required|numeric|min:0',
            'min_savings' => 'required|numeric|min:0',
            'max_loan_multiplier' => 'required|integer|min:1',
            'interest_rate_discount' => 'required|numeric|min:0|max:100',
            'can_vote' => 'required|boolean',
            'can_hold_office' => 'required|boolean',
            'priority' => 'required|integer|min:0',
            'status' => 'required|in:active,inactive',
        ]);

        $memberType->update($validated);

        ActivityLog::create([
            'user_id' => Auth::id(),
            'description' => 'Admin updated member type: ' . $memberType->name,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        $this->success('Member type updated successfully.');

        return redirect()->route('admin.member-types.index');
    }

    public function destroy(Request $request, string $encryptedId)
    {
        Gate::authorize('admin-only');

        try {
            $id = $this->encryptedIdService->decrypt($encryptedId);
        } catch (\Exception $e) {
            return redirect()->route('admin.member-types.index')
                ->with('error', 'Invalid member type ID.');
        }

        $memberType = MemberType::findOrFail($id);
        $memberTypeName = $memberType->name;

        if ($memberType->users()->count() > 0) {
            $this->error('Cannot delete member type with associated members.');
            return redirect()->route('admin.member-types.index');
        }

        $memberType->delete();

        ActivityLog::create([
            'user_id' => Auth::id(),
            'description' => 'Admin deleted member type: ' . $memberTypeName,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        $this->success('Member type deleted successfully.');

        return redirect()->route('admin.member-types.index');
    }
}
