<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TelegramSetting;

class SettingsController extends Controller
{
    public function index() {
        $settings = TelegramSetting::all();
        return view('settings.index', compact('settings'));
    }

    public function create()  {
        return view('settings.create');
    }

    public function store() {
        $data = request()->validate([
            'telegram_token' => 'string',
            'moodle_token' => 'string',
            'moodle_url' => 'string'
        ]);
        TelegramSetting::create($data);
        return redirect()->route('settings.index');
    }

    public function show(TelegramSetting $setting) {
        return view('settings.show', compact('setting'));
    }

    public function edit(TelegramSetting $setting) {
        return view('settings.edit', compact('setting'));
    }

    public function update(TelegramSetting $setting) {
        $data = request()->validate([
            'telegram_token' => '',
            'moodle_token' => '',
            'moodle_url' => ''
        ]);
        $setting->update($data);
        return redirect()->route('settings.show', $setting->id);
    }

    public function destroy(TelegramSetting $setting) {
        $setting->delete();
        return redirect()->route('settings.index');
    }
}
