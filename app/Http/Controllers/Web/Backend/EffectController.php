<?php

namespace App\Http\Controllers\Web\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Effect;
use App\Models\Frame;
use Illuminate\Support\Facades\DB;

class EffectController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $effects = Effect::with('frame')->select('effects.*');
            return datatables()->of($effects)
                ->addIndexColumn()
                ->addColumn('image', function ($row) {
                    return $row->image ? '<img src="' . asset($row->image) . '" style="max-width:60px;">' : '-';
                })
                ->addColumn('frame', function ($row) {
                    return $row->frame ? $row->frame->name : '-';
                })
                ->addColumn('action', function ($row) {
                    $editUrl = route('admin.effects.edit', $row->id);
                    $deleteBtn = "<a href='#' onclick='showDeleteConfirm($row->id)' class='btn btn-danger btn-sm'>Delete</a>";
                    $editBtn = "<a href='$editUrl' class='btn btn-primary btn-sm'>Edit</a>";
                    return "$editBtn $deleteBtn";
                })
                ->rawColumns(['image', 'action'])
                ->make(true);
        }
        return view('backend.layouts.effect.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $frames = Frame::all();
        return view('backend.layouts.effect.create', compact('frames'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'image' => 'required|image|mimes:jpeg,png,jpg,svg|max:5120',
            'frame_id' => 'nullable|exists:frames,id',
        ]);
        $imagePath = $request->hasFile('image') ? uploadImage($request->file('image'), 'effects') : null;
        Effect::create([
            'name' => $request->name,
            'image' => $imagePath,
            'frame_id' => $request->frame_id,
        ]);
        return redirect()->route('admin.effects.index')->with('t-success', 'Effect created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $effect = Effect::findOrFail($id);
        $frames = Frame::all();
        return view('backend.layouts.effect.edit', compact('effect', 'frames'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $effect = Effect::findOrFail($id);
        $request->validate([
            'name' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,svg|max:5120',
            'frame_id' => 'nullable|exists:frames,id',
        ]);
        $data = $request->only(['name', 'frame_id']);
        if ($request->hasFile('image')) {
            if ($effect->image && file_exists(public_path($effect->image))) {
                unlink(public_path($effect->image));
            }
            $data['image'] = uploadImage($request->file('image'), 'effects');
        }
        $effect->update($data);
        return redirect()->route('admin.effects.index')->with('t-success', 'Effect updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $effect = Effect::findOrFail($id);
        if ($effect->image && file_exists(public_path($effect->image))) {
            unlink(public_path($effect->image));
        }
        $effect->delete();
        return response()->json(['success' => true, 'message' => 'Effect deleted successfully.']);
    }
}
