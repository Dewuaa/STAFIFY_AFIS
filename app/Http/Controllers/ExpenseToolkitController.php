<?php

namespace App\Http\Controllers;

use App\Models\ExpenseToolkit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ExpenseToolkitController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $toolkits = ExpenseToolkit::accessible($user->user_id)
            ->orderBy('created_at', 'desc')
            ->get();

        $iconsPath = public_path('HRIS/hr-toolkit/assets/crm_hr/');
        $icons = [];
        if (is_dir($iconsPath)) {
            $files = scandir($iconsPath);
            $icons = array_filter($files, function ($file) {
                return pathinfo($file, PATHINFO_EXTENSION) === 'gif';
            });
        }

        return view('pages.expense-logsheet', compact('toolkits', 'icons', 'iconsPath'));
    }

    public function store(Request $request)
    {
        $user = Auth::user();

        $validator = Validator::make($request->all(), [
            'sales_title' => 'required|string|max:50',
            'form_url' => 'nullable|url',
            'response_url' => 'nullable|url',
            'icon' => 'required|string',
            'type' => 'required|in:Form,Sheet,Video,Slides,Folder,Form+Sheet,Website',
            'toolkit_id' => 'nullable|integer',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $type = $request->input('type');
        if (in_array($type, ['Video', 'Slides', 'Folder', 'Form']) && !$request->filled('form_url')) {
            return back()->withErrors(['form_url' => 'Form/Video/Slides/Folder requires a valid URL'])->withInput();
        }
        if ($type === 'Sheet' && !$request->filled('response_url')) {
            return back()->withErrors(['response_url' => 'Sheet requires a Sheet URL'])->withInput();
        }
        if ($type === 'Form+Sheet' && (! $request->filled('form_url') || ! $request->filled('response_url'))) {
            return back()->withErrors(['sales_title' => 'Form + Sheet requires Form and Sheet URLs'])->withInput();
        }

        $attributes = $request->only(['sales_title', 'form_url', 'response_url', 'icon', 'type']);
        $attributes['user_id'] = $user->user_id;

        if ($request->filled('toolkit_id')) {
            $toolkit = ExpenseToolkit::findOrFail($request->input('toolkit_id'));
            if ($toolkit->user_id !== $user->user_id) {
                abort(403);
            }
            $toolkit->update($attributes);
            return back()->with('success', 'Toolkit updated successfully');
        }

        ExpenseToolkit::create($attributes);
        return back()->with('success', 'Toolkit created successfully');
    }

    public function update($id, Request $request)
    {
        $request->merge(['toolkit_id' => $id]);
        return $this->store($request);
    }
}


