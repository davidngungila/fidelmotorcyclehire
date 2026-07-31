<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\SwfBenefit;
use App\Models\SwfMember;
use App\Traits\FlashMessages;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class SwfBenefitController extends Controller
{
    use FlashMessages;

    public function index(): View
    {
        $benefits = SwfBenefit::where('is_active', true)->get();
        $swfMembers = SwfMember::with('user')->where('is_active', true)->get();
        
        return view('admin.swf.benefits.index', [
            'benefits' => $benefits,
            'swfMembers' => $swfMembers,
        ]);
    }

    public function create(): View
    {
        return view('admin.swf.benefits.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category' => 'required|string',
            'max_amount' => 'nullable|numeric|min:0',
            'requires_approval' => 'nullable|boolean',
        ]);

        try {
            SwfBenefit::create([
                'name' => $request->input('name'),
                'description' => $request->input('description'),
                'category' => $request->input('category'),
                'max_amount' => $request->input('max_amount'),
                'requires_approval' => $request->input('requires_approval', true),
                'is_active' => true,
            ]);

            ActivityLog::create([
                'user_id' => Auth::id(),
                'subject_type' => 'swf_benefit',
                'subject_id' => null,
                'description' => "Admin created new SWF benefit: {$request->input('name')}",
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);

            $this->success('Benefit created successfully!');
            return redirect()->route('admin.swf.benefits.index');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to create benefit: ' . $e->getMessage());
        }
    }

    public function grant(Request $request)
    {
        $request->validate([
            'swf_member_id' => 'required|exists:swf_members,id',
            'swf_benefit_id' => 'required|exists:swf_benefits,id',
            'amount' => 'required|numeric|min:0',
            'received_date' => 'required|date',
        ]);

        try {
            $swfMember = SwfMember::findOrFail($request->input('swf_member_id'));
            $swfBenefit = SwfBenefit::findOrFail($request->input('swf_benefit_id'));

            $swfMember->benefits()->attach($swfBenefit->id, [
                'amount' => $request->input('amount'),
                'received_date' => $request->input('received_date'),
                'status' => 'approved',
            ]);

            // Update member total benefits received
            $swfMember->total_benefits_received += $request->input('amount');
            $swfMember->save();

            ActivityLog::create([
                'user_id' => Auth::id(),
                'subject_type' => 'swf_member_benefit',
                'subject_id' => null,
                'description' => "Admin granted benefit {$swfBenefit->name} to {$swfMember->membership_number}",
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'properties' => [
                    'swf_member_id' => $swfMember->id,
                    'swf_benefit_id' => $swfBenefit->id,
                    'amount' => $request->input('amount'),
                ],
            ]);

            $this->success('Benefit granted successfully!');
            return redirect()->route('admin.swf.benefits.index');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to grant benefit: ' . $e->getMessage());
        }
    }
}
