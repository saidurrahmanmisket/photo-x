<?php

namespace App\Http\Controllers\Web\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Frame;
use Illuminate\Support\Facades\DB;

class FrameController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $frames = Frame::query();
            return datatables()->of($frames)
                ->addIndexColumn()
                ->addColumn('image', function ($row) {
                    return $row->image ? '<img src="' . asset($row->image) . '" style="max-width:60px;">' : '-';
                })
                ->addColumn('grid', function ($row) {
                    return $row->grid_columns . ' x ' . $row->grid_rows;
                })
                ->addColumn('action', function ($row) {
                    $editUrl = route('admin.frames.edit', $row->id);
                    $deleteBtn = "<a href='#' onclick='showDeleteConfirm($row->id)' class='btn btn-danger btn-sm'>Delete</a>";
                    $editBtn = "<a href='$editUrl' class='btn btn-primary btn-sm'>Edit</a>";
                    return "$editBtn $deleteBtn";
                })
                ->rawColumns(['image', 'action'])
                ->make(true);
        }
        return view('backend.layouts.frame.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $kiosks = \App\Models\Kiosk::all();
        $gridOptions = [
            '2x2' => '2 x 2',
            '3x2' => '3 x 2',
            '1x1' => '1 x 1',
            '3x3' => '3 x 3',
        ];
        return view('backend.layouts.frame.create', compact('kiosks', 'gridOptions'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'image' => 'required|image|mimes:jpeg,png,jpg,svg,webp|max:5120',
            'grid_option' => 'required|in:2x2,3x2,1x1,3x3',
            'price' => 'required|numeric|min:0',
            'kiosk_ids' => 'required|array',
            'kiosk_ids.*' => 'exists:kiosks,id',
        ]);
        $imagePath = $request->hasFile('image') ? uploadImage($request->file('image'), 'frames') : null;
        list($grid_columns, $grid_rows) = explode('x', $request->grid_option);
        $frame = Frame::create([
            'name' => $request->name,
            'image' => $imagePath,
            'grid_columns' => $grid_columns,
            'grid_rows' => $grid_rows,
            'price' => $request->price,
        ]);
        $kioskIds = $request->kiosk_ids;
        if (in_array('all', $kioskIds)) {
            $kioskIds = \App\Models\Kiosk::pluck('id')->toArray();
        }
        $frame->kiosks()->sync($kioskIds);
        return redirect()->route('admin.frames.index')->with('t-success', 'Frame created successfully.');
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
        $frame = Frame::findOrFail($id);
        $kiosks = \App\Models\Kiosk::all();
        $selectedKiosks = $frame->kiosks->pluck('id')->toArray();
        $gridOptions = [
            '2x2' => '2 x 2',
            '3x2' => '3 x 2',
            '1x1' => '1 x 1',
            '3x3' => '3 x 3',
        ];
        $selectedGrid = $frame->grid_columns . 'x' . $frame->grid_rows;
        return view('backend.layouts.frame.edit', compact('frame', 'kiosks', 'selectedKiosks', 'gridOptions', 'selectedGrid'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $frame = Frame::findOrFail($id);
        $request->validate([
            'name' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,svg,webp|max:5120',
            'grid_option' => 'required|in:2x2,3x2,1x1,3x3',
            'price' => 'required|numeric|min:0',
            'kiosk_ids' => 'required|array',
            'kiosk_ids.*' => 'exists:kiosks,id',
        ]);
        list($grid_columns, $grid_rows) = explode('x', $request->grid_option);
        $data = $request->only(['name', 'price']);
        $data['grid_columns'] = $grid_columns;
        $data['grid_rows'] = $grid_rows;
        if ($request->hasFile('image')) {
            if ($frame->image && file_exists(public_path($frame->image))) {
                unlink(public_path($frame->image));
            }
            $data['image'] = uploadImage($request->file('image'), 'frames');
        }
        $frame->update($data);
        $kioskIds = $request->kiosk_ids;
        if (in_array('all', $kioskIds)) {
            $kioskIds = \App\Models\Kiosk::pluck('id')->toArray();
        }
        $frame->kiosks()->sync($kioskIds);
        return redirect()->route('admin.frames.index')->with('t-success', 'Frame updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $frame = Frame::findOrFail($id);
        if ($frame->image && file_exists(public_path($frame->image))) {
            unlink(public_path($frame->image));
        }
        $frame->delete();
        return response()->json(['success' => true, 'message' => 'Frame deleted successfully.']);
    }
}
