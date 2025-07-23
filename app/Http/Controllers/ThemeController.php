<?php

namespace App\Http\Controllers;

use App\Models\Theme;
use Illuminate\Http\Request;

class ThemeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $themes = Theme::query();
            return datatables()->of($themes)
                ->addIndexColumn()
                ->addColumn('image', function ($row) {
                    return $row->image ? '<img src="' . asset($row->image) . '" style="max-width:60px;">' : '-';
                })
                ->addColumn('action', function ($row) {
                    $editUrl = route('admin.themes.edit', $row->id);
                    $showUrl = route('admin.themes.show', $row->id);
                    $deleteBtn = "<a href='#' onclick='showDeleteConfirm($row->id)' class='btn btn-danger btn-sm'>Delete</a>";
                    $editBtn = "<a href='$editUrl' class='btn btn-primary btn-sm'>Edit</a>";
                    $showBtn = "<a href='$showUrl' class='btn btn-info btn-sm'>View</a>";
                    return "$showBtn $editBtn $deleteBtn";
                })
                ->rawColumns(['image', 'action'])
                ->make(true);
        }
        return view('backend.layouts.theme.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('backend.layouts.theme.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'image' => 'required|image|mimes:jpeg,png,jpg,svg,webp|max:5120',
        ]);
        $imagePath = $request->hasFile('image') ? uploadImage($request->file('image'), 'themes') : null;
        $theme = Theme::create([
            'name' => $request->name,
            'price' => $request->price,
            'image' => $imagePath,
        ]);
        return redirect()->route('admin.themes.index')->with('t-success', 'Theme created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Theme $theme)
    {
        return view('backend.layouts.theme.show', compact('theme'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Theme $theme)
    {
        return view('backend.layouts.theme.edit', compact('theme'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Theme $theme)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,svg,webp|max:5120',
        ]);
        $data = $request->only(['name', 'price']);
        if ($request->hasFile('image')) {
            if ($theme->image && file_exists(public_path($theme->image))) {
                unlink(public_path($theme->image));
            }
            $data['image'] = uploadImage($request->file('image'), 'themes');
        }
        $theme->update($data);
        return redirect()->route('admin.themes.index')->with('t-success', 'Theme updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Theme $theme)
    {
        if ($theme->image && file_exists(public_path($theme->image))) {
            unlink(public_path($theme->image));
        }
        $theme->delete();
        return response()->json(['success' => true, 'message' => 'Theme deleted successfully.']);
    }
}
