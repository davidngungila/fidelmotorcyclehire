<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SavingPlan;
use App\Traits\FlashMessages;
use Illuminate\Http\Request;

class SavingPlanController extends Controller
{
    use FlashMessages;

    public function index(Request $request)
    {
        $query = SavingPlan::query();

        if ($request->filled('memberid')) {
            $query->byMemberId($request->memberid);
        }

        if ($request->filled('membership')) {
            $query->byMembership($request->membership);
        }

        $savingPlans = $query->orderBy('created_at', 'desc')->paginate(25);

        return view('admin.saving-plans.index', compact('savingPlans'));
    }

    public function create()
    {
        return view('admin.saving-plans.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'memberid' => 'required|string|max:50',
            'membership' => 'required|string|max:50',
            'monthly_goal' => 'required|numeric|min:0',
            'goal' => 'required|numeric|min:0',
        ]);

        SavingPlan::create($validated);

        $this->success('Saving plan created successfully.');
        return redirect()->route('admin.saving-plans.index');
    }

    public function edit(SavingPlan $savingPlan)
    {
        return view('admin.saving-plans.edit', compact('savingPlan'));
    }

    public function update(Request $request, SavingPlan $savingPlan)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'memberid' => 'required|string|max:50',
            'membership' => 'required|string|max:50',
            'monthly_goal' => 'required|numeric|min:0',
            'goal' => 'required|numeric|min:0',
        ]);

        $savingPlan->update($validated);

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
