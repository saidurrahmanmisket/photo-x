<?php

namespace App\Http\Controllers\Web\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Kiosk;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class KioskController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $kiosks = Kiosk::query();
            return datatables()->of($kiosks)
                ->addIndexColumn()
                ->addColumn('status', function ($row) {
                    $status = '<div class="form-check form-switch">';
                    $status .= '<input onclick="showStatusChangeAlert(' . $row->id . ')" type="checkbox" class="form-check-input" id="customSwitch' . $row->id . '" getAreaid="' . $row->id . '" name="status"';
                    if ($row->status == "active") {
                        $status .= "checked";
                    }
                    $status .= '><label for="customSwitch' . $row->id . '" class="form-check-label" for="customSwitch"></label></div>';
                    return $status;
                })
                ->addColumn('action', function ($row) {
                    $editUrl = route('admin.kiosks.edit', $row->id);
                    $deleteBtn = "<a href='#' onclick='showDeleteConfirm($row->id)' class='btn btn-danger btn-sm'>Delete</a>";
                    $editBtn = "<a href='$editUrl' class='btn btn-primary btn-sm'>Edit</a>";
                    return "$editBtn $deleteBtn";
                })
                ->rawColumns(['status', 'action'])
                ->make(true);
        }
        return view('backend.layouts.kiosk.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('backend.layouts.kiosk.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'device_id' => 'required|string|max:255|unique:kiosks,device_id',
            'status' => 'required|in:active,inactive',
            'activation_code' => 'nullable|string|max:255',
            'max_clicks' => 'nullable|integer|min:1',
            'max_capture_seconds' => 'nullable|integer|min:1',
        ]);
        Kiosk::create($request->only(['name', 'device_id', 'status', 'activation_code', 'max_clicks', 'max_capture_seconds']));
        return redirect()->route('admin.kiosks.index')->with('t-success', 'Kiosk created successfully.');
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
        $kiosk = Kiosk::findOrFail($id);
        return view('backend.layouts.kiosk.edit', compact('kiosk'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $kiosk = Kiosk::findOrFail($id);
        $request->validate([
            'name' => 'required|string|max:255',
            'device_id' => 'required|string|max:255|unique:kiosks,device_id,' . $kiosk->id,
            'status' => 'required|in:active,inactive',
            'activation_code' => 'nullable|string|max:255',
            'max_clicks' => 'nullable|integer|min:1',
            'max_capture_seconds' => 'nullable|integer|min:1',
        ]);
        $kiosk->update($request->only(['name', 'device_id', 'status', 'activation_code', 'max_clicks', 'max_capture_seconds']));
        return redirect()->route('admin.kiosks.index')->with('t-success', 'Kiosk updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $kiosk = Kiosk::findOrFail($id);
        $kiosk->delete();
        return response()->json(['success' => true, 'message' => 'Kiosk deleted successfully.']);
    }

    /**
     * Toggle the status of the specified kiosk.
     */
    public function status($id)
    {
        $kiosk = Kiosk::findOrFail($id);
        $kiosk->status = $kiosk->status === 'active' ? 'inactive' : 'active';
        $kiosk->save();
        return response()->json([
            'success' => true,
            'message' => 'Status updated successfully.',
            'data' => $kiosk,
        ]);
    }
}
