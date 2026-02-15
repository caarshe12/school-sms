<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SettingController extends Controller
{
    public function index()
    {
        // Fetch all settings as key-value pairs
        $settings = Setting::all()->pluck('value', 'key');
        return view('settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $data = $request->except(['_token', 'school_logo']);

        // Handle Text Inputs
        foreach ($data as $key => $value) {
            Setting::updateOrCreate(['key' => $key], ['value' => $value]);
        }

        // Handle Logo Upload
        if ($request->hasFile('school_logo')) {
            $file = $request->file('school_logo');
            $path = $file->store('public/settings'); // Storage/app/public/settings
            
            // Save relative path (remove public/) to be accessible via storage link
            $publicPath = str_replace('public/', '', $path);
            
            Setting::updateOrCreate(['key' => 'school_logo'], ['value' => $publicPath]);
        }

        \Illuminate\Support\Facades\Cache::forget('school_settings');

        return back()->with('success', 'Settings updated successfully.');
    }
}
