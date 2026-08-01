<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class ShareSettingController extends Controller
{
    public function index()
    {
        $settings = Cache::get('share_settings', [
            'enable_share_purchases' => true,
            'enable_share_transfers' => true,
            'enable_share_dividends' => true,
                    'minimum_purchase_amount' => 1000,
            'maximum_purchase_amount' => null,
            'transfer_fee_percentage' => 0,
            'dividend_tax_percentage' => 0,
            'certificate_auto_generate' => true,
            'notification_email' => null,
        ]);

        return view('admin.share-settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'enable_share_purchases' => 'boolean',
            'enable_share_transfers' => 'boolean',
            'enable_share_dividends' => 'boolean',
            'minimum_purchase_amount' => 'nullable|numeric|min:0',
            'maximum_purchase_amount' => 'nullable|numeric|min:0',
            'transfer_fee_percentage' => 'nullable|numeric|min:0|max:100',
            'dividend_tax_percentage' => 'nullable|numeric|min:0|max:100',
            'certificate_auto_generate' => 'boolean',
            'notification_email' => 'nullable|email',
        ]);

        Cache::put('share_settings', $validated);

        return redirect()->route('admin.share-settings.index')
            ->with('success', 'Share settings updated successfully.');
    }
}
