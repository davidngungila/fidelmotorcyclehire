<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Motorcycle;
use App\Services\EncryptedIdService;
use Illuminate\Http\Request;

class MotorcycleController extends Controller
{
    public function __construct(
        protected EncryptedIdService $encryptedIdService,
    ) {
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $motorcycles = Motorcycle::with('assignedUser')->latest()->paginate(15);
        
        // Add encrypted IDs to each motorcycle
        $motorcycles->getCollection()->transform(function ($motorcycle) {
            $motorcycle->encrypted_id = $this->encryptedIdService->encrypt($motorcycle->id);
            return $motorcycle;
        });
        
        return view('admin.motorcycles.index', compact('motorcycles'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.motorcycles.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'brand' => 'required|string|max:255',
            'model' => 'required|string|max:255',
            'engine_number' => 'required|string|unique:motorcycles,engine_number',
            'chassis_number' => 'required|string|unique:motorcycles,chassis_number',
            'registration_number' => 'nullable|string|unique:motorcycles,registration_number',
            'colour' => 'required|string|max:255',
            'purchase_price' => 'required|numeric|min:0',
            'selling_price' => 'nullable|numeric|min:0',
            'status' => 'required|in:Available,Assigned,Sold,Under Repair',
            'notes' => 'nullable|string',
            'purchase_date' => 'nullable|date',
        ]);

        Motorcycle::create($validated);

        return redirect()->route('admin.motorcycles.index')
            ->with('success', 'Motorcycle added successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $encryptedId)
    {
        try {
            $id = $this->encryptedIdService->decrypt($encryptedId);
        } catch (\Exception $e) {
            return redirect()->route('admin.motorcycles.index')
                ->with('error', 'Invalid motorcycle ID.');
        }

        $motorcycle = Motorcycle::with('assignedUser')->findOrFail($id);
        return view('admin.motorcycles.show', compact('motorcycle'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $encryptedId)
    {
        try {
            $id = $this->encryptedIdService->decrypt($encryptedId);
        } catch (\Exception $e) {
            return redirect()->route('admin.motorcycles.index')
                ->with('error', 'Invalid motorcycle ID.');
        }

        $motorcycle = Motorcycle::findOrFail($id);
        return view('admin.motorcycles.edit', compact('motorcycle'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $encryptedId)
    {
        try {
            $id = $this->encryptedIdService->decrypt($encryptedId);
        } catch (\Exception $e) {
            return redirect()->route('admin.motorcycles.index')
                ->with('error', 'Invalid motorcycle ID.');
        }

        $motorcycle = Motorcycle::findOrFail($id);

        $validated = $request->validate([
            'brand' => 'required|string|max:255',
            'model' => 'required|string|max:255',
            'engine_number' => 'required|string|unique:motorcycles,engine_number,' . $motorcycle->id,
            'chassis_number' => 'required|string|unique:motorcycles,chassis_number,' . $motorcycle->id,
            'registration_number' => 'nullable|string|unique:motorcycles,registration_number,' . $motorcycle->id,
            'colour' => 'required|string|max:255',
            'purchase_price' => 'required|numeric|min:0',
            'selling_price' => 'nullable|numeric|min:0',
            'status' => 'required|in:Available,Assigned,Sold,Under Repair',
            'notes' => 'nullable|string',
            'assigned_to' => 'nullable|exists:users,id',
            'purchase_date' => 'nullable|date',
            'sale_date' => 'nullable|date',
        ]);

        $motorcycle->update($validated);

        return redirect()->route('admin.motorcycles.index')
            ->with('success', 'Motorcycle updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $encryptedId)
    {
        try {
            $id = $this->encryptedIdService->decrypt($encryptedId);
        } catch (\Exception $e) {
            return redirect()->route('admin.motorcycles.index')
                ->with('error', 'Invalid motorcycle ID.');
        }

        $motorcycle = Motorcycle::findOrFail($id);
        $motorcycle->delete();

        return redirect()->route('admin.motorcycles.index')
            ->with('success', 'Motorcycle deleted successfully.');
    }
}
