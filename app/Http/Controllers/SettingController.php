<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function index(): \Illuminate\View\View
    {
        $settings = Setting::all()->pluck('value', 'key');

        return view('settings.index', compact('settings'));
    }

    public function update(Request $request): \Illuminate\Http\RedirectResponse
    {
        $data = $request->except('_token');

        foreach ($data as $key => $value) {
            Setting::updateOrCreate(
                ['key' => $key],
                ['value' => $value, 'group' => 'general']
            );
        }

        return back()->with('success', 'System parameters updated successfully.');
    }
}
