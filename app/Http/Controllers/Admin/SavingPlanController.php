<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SavingPlan;
use App\Models\User;
use App\Traits\FlashMessages;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SavingPlanController extends Controller
{
    use FlashMessages;

    public function index(Request $request)
    {
        $query = SavingPlan::query();

        if ($request->filled('member_number')) {
            $query->byMemberNumber($request->member_number);
        }

        if ($request->filled('membership')) {
            $query->byMembership($request->membership);
        }

        $savingPlans = $query->orderBy('created_at', 'desc')->paginate(25);

        return view('admin.saving-plans.index', compact('savingPlans'));
    }

    public function create()
    {
        $members = User::where('role', 'member')->get();
        return view('admin.saving-plans.create', compact('members'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'user_id' => 'required|exists:users,id',
            'monthly_goal' => 'required|numeric|min:0',
            'goal' => 'required|numeric|min:0',
            'target_date' => 'nullable|date',
            'status' => 'nullable|string|in:active,completed,paused',
        ]);

        $user = User::findOrFail($validated['user_id']);
        
        SavingPlan::create([
            'name' => $validated['name'],
            'member_number' => $user->member_number,
            'membership' => 'individual', // Default to individual
            'monthly_goal' => $validated['monthly_goal'],
            'goal' => $validated['goal'],
            'target_date' => $validated['target_date'] ?? null,
            'status' => $validated['status'] ?? 'active',
        ]);

        $this->success('Saving plan created successfully.');
        return redirect()->route('admin.saving-plans.index');
    }

    public function edit(SavingPlan $savingPlan)
    {
        $members = User::where('role', 'member')->get();
        return view('admin.saving-plans.edit', compact('savingPlan', 'members'));
    }

    public function update(Request $request, SavingPlan $savingPlan)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'user_id' => 'required|exists:users,id',
            'monthly_goal' => 'required|numeric|min:0',
            'goal' => 'required|numeric|min:0',
            'target_date' => 'nullable|date',
            'status' => 'nullable|string|in:active,completed,paused',
        ]);

        $user = User::findOrFail($validated['user_id']);
        
        $savingPlan->update([
            'name' => $validated['name'],
            'member_number' => $user->member_number,
            'membership' => 'individual',
            'monthly_goal' => $validated['monthly_goal'],
            'goal' => $validated['goal'],
            'target_date' => $validated['target_date'] ?? null,
            'status' => $validated['status'] ?? 'active',
        ]);

        $this->success('Saving plan updated successfully.');
        return redirect()->route('admin.saving-plans.index');
    }

    public function destroy(SavingPlan $savingPlan)
    {
        $savingPlan->delete();

        $this->success('Saving plan deleted successfully.');
        return redirect()->route('admin.saving-plans.index');
    }
}
