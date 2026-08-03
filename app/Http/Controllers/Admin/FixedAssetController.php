<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FixedAsset;
use App\Models\Account;
use App\Models\User;
use Illuminate\Http\Request;

class FixedAssetController extends Controller
{
    public function index()
    {
        $fixedAssets = FixedAsset::with(['account', 'responsiblePerson'])
            ->orderBy('purchase_date', 'desc')
            ->get();
        
        return view('admin.fixed-assets.index', compact('fixedAssets'));
    }

    public function create()
    {
        $accounts = Account::where('account_type', 'asset')
            ->where('account_subtype', 'fixed_asset')
            ->where('is_active', true)
            ->orderBy('account_code')
            ->get();
        
        $responsiblePersons = User::orderBy('name')->get();
        
        return view('admin.fixed-assets.create', compact('accounts', 'responsiblePersons'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'account_id' => 'required|exists:accounts,id',
            'asset_name' => 'required|string|max:255',
            'asset_code' => 'required|string|max:50|unique:fixed_assets',
            'description' => 'nullable|string',
            'purchase_date' => 'required|date',
            'purchase_cost' => 'required|numeric|min:0',
            'salvage_value' => 'nullable|numeric|min:0',
            'useful_life_years' => 'required|integer|min:1',
            'depreciation_method' => 'required|in:straight_line,declining_balance,double_declining_balance',
            'location' => 'nullable|string|max:255',
            'responsible_person_id' => 'nullable|exists:users,id',
            'serial_number' => 'nullable|string|max:100',
            'is_active' => 'boolean',
            'notes' => 'nullable|string',
        ]);

        $validated['is_active'] = $request->has('is_active');
        $validated['current_value'] = $validated['purchase_cost'];
        $validated['accumulated_depreciation'] = 0;

        FixedAsset::create($validated);

        return redirect()->route('admin.fixed-assets.index')
            ->with('success', 'Fixed asset created successfully.');
    }

    public function show($id)
    {
        $fixedAsset = FixedAsset::with(['account', 'responsiblePerson'])->findOrFail($id);
        
        return view('admin.fixed-assets.show', compact('fixedAsset'));
    }

    public function edit($id)
    {
        $fixedAsset = FixedAsset::findOrFail($id);
        $accounts = Account::where('account_type', 'asset')
            ->where('account_subtype', 'fixed_asset')
            ->where('is_active', true)
            ->orderBy('account_code')
            ->get();
        
        $responsiblePersons = User::orderBy('name')->get();
        
        return view('admin.fixed-assets.edit', compact('fixedAsset', 'accounts', 'responsiblePersons'));
    }

    public function update(Request $request, $id)
    {
        $fixedAsset = FixedAsset::findOrFail($id);

        $validated = $request->validate([
            'account_id' => 'required|exists:accounts,id',
            'asset_name' => 'required|string|max:255',
            'asset_code' => 'required|string|max:50|unique:fixed_assets,asset_code,' . $id,
            'description' => 'nullable|string',
            'purchase_date' => 'required|date',
            'purchase_cost' => 'required|numeric|min:0',
            'salvage_value' => 'nullable|numeric|min:0',
            'useful_life_years' => 'required|integer|min:1',
            'depreciation_method' => 'required|in:straight_line,declining_balance,double_declining_balance',
            'location' => 'nullable|string|max:255',
            'responsible_person_id' => 'nullable|exists:users,id',
            'serial_number' => 'nullable|string|max:100',
            'is_active' => 'boolean',
            'notes' => 'nullable|string',
        ]);

        $validated['is_active'] = $request->has('is_active');

        $fixedAsset->update($validated);

        return redirect()->route('admin.fixed-assets.index')
            ->with('success', 'Fixed asset updated successfully.');
    }

    public function destroy($id)
    {
        $fixedAsset = FixedAsset::findOrFail($id);
        $fixedAsset->delete();

        return redirect()->route('admin.fixed-assets.index')
            ->with('success', 'Fixed asset deleted successfully.');
    }

    public function calculateDepreciation($id)
    {
        $fixedAsset = FixedAsset::findOrFail($id);
        
        try {
            $fixedAsset->updateDepreciation();
            return back()->with('success', 'Depreciation calculated and updated successfully.');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }
}
