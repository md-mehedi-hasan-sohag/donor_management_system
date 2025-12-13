<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PlatformSetting;
use App\Models\EmailTemplate;
use App\Models\StaticPage;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    public function index()
    {
        $settings = PlatformSetting::all()->pluck('value', 'key');

        return view('admin.settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        foreach ($request->except('_token') as $key => $value) {
            PlatformSetting::set($key, $value);
        }

        return back()->with('success', 'Settings updated successfully!');
    }

    public function emailTemplates()
    {
        $templates = EmailTemplate::all();

        return view('admin.settings.email-templates', compact('templates'));
    }

    public function updateEmailTemplate(Request $request, EmailTemplate $template)
    {
        $request->validate([
            'subject' => 'required|string|max:255',
            'body' => 'required|string',
        ]);

        $template->update($request->only('subject', 'body'));

        return back()->with('success', 'Email template updated!');
    }

    public function staticPages()
    {
        $pages = StaticPage::all();

        return view('admin.settings.static-pages', compact('pages'));
    }

    public function updateStaticPage(Request $request, StaticPage $page)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'is_published' => 'boolean',
        ]);

        $page->update($request->all());

        return back()->with('success', 'Page updated successfully!');
    }
}