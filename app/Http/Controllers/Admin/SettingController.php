<?php

namespace App\Http\Controllers\Admin;

use App\Models\Setting;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SettingController extends Controller
{
    public function index()
    {
        $setting = Setting::first();
        return view('admin.setting.index', compact('setting'));
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'alias' => 'required|string|max:255',
            'description' => 'required|string',
            'phone' => 'nullable|string',
            'address' => 'nullable|string',
            'map' => 'nullable|string',
            'email' => 'nullable|email',
            'logo' => 'nullable|image|max:2048',
            'social_media' => 'nullable|array',
            'social_media.*.platform' => 'nullable|string|max:255',
            'social_media.*.link' => 'nullable|url|max:255',
        ]);

        if ($request->file('logo')) {
            $validatedData['logo'] = $request->file('logo')->store('logo', 'public');
        }

        $validatedData['social_media'] = $request->has('social_media')
            ? json_encode($request->social_media)
            : null;

        Setting::updateOrCreate(
            ['id' => 1],
            $validatedData
        );

        return redirect()->route('dashboard.admin.setting.index')->with('success', 'Pengaturan berhasil disimpan!');
    }
}
