<?php

namespace App\Http\Controllers\Web\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Photo;
use App\Models\Customer;
use App\Models\Kiosk;
use App\Models\Frame;
use App\Models\Effect;
use Illuminate\Support\Facades\DB;

class PhotoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $photos = Photo::with(['customer', 'kiosk', 'frame', 'effect'])->select('photos.*');
            return datatables()->of($photos)
                ->addIndexColumn()
                ->addColumn('customer', function ($row) {
                    return $row->customer ? $row->customer->name : '-';
                })
                ->addColumn('kiosk', function ($row) {
                    return $row->kiosk ? $row->kiosk->name : '-';
                })
                ->addColumn('frame', function ($row) {
                    return $row->frame ? $row->frame->name : '-';
                })
                ->addColumn('effect', function ($row) {
                    return $row->effect ? $row->effect->name : '-';
                })
                ->addColumn('action', function ($row) {
                    $showUrl = route('admin.photos.show', $row->id);
                    $deleteBtn = "<a href='#' onclick='showDeleteConfirm($row->id)' class='btn btn-danger btn-sm'>Delete</a>";
                    $showBtn = "<a href='$showUrl' class='btn btn-info btn-sm'>View</a>";
                    return "$showBtn $deleteBtn";
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('backend.layouts.photo.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $photo = Photo::with(['customer', 'kiosk', 'frame', 'effect'])->findOrFail($id);
        return view('backend.layouts.photo.show', compact('photo'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $photo = Photo::findOrFail($id);
        $photo->delete();
        return response()->json(['success' => true, 'message' => 'Photo deleted successfully.']);
    }
}
