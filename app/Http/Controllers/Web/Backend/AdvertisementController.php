<?php

namespace App\Http\Controllers\Web\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Advertisement;
use App\Models\AdvertisementMedia;
use Illuminate\Support\Facades\DB;

class AdvertisementController extends Controller
{
    public function index(Request $request)
    {
        $ads = Advertisement::with('media')->latest()->get();
        return view('backend.layouts.advertisement.index', compact('ads'));
    }

    public function create()
    {
        $kiosks = \App\Models\Kiosk::all();
        return view('backend.layouts.advertisement.create', compact('kiosks'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'status' => 'required|in:active,inactive',
            'media.*' => 'required|file|mimes:jpeg,png,jpg,webp,mp4,mov,avi|max:20480',
            'kiosk_ids' => 'nullable|array',
            'kiosk_ids.*' => 'exists:kiosks,id',
        ]);
        DB::beginTransaction();
        try {
            $ad = Advertisement::create($request->only(['title', 'status']));
            if ($request->hasFile('media')) {
                foreach ($request->file('media') as $file) {
                    $type = in_array($file->extension(), ['mp4', 'mov', 'avi']) ? 'video' : 'image';
                    $path = uploadImage($file, 'advertisements');
                    AdvertisementMedia::create([
                        'advertisement_id' => $ad->id,
                        'file_path' => $path,
                        'type' => $type,
                    ]);
                }
            }
            $ad->kiosks()->sync($request->input('kiosk_ids', []));
            DB::commit();
            return redirect()->route('admin.advertisements.index')->with('t-success', 'Advertisement created successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('t-error', $e->getMessage());
        }
    }

    public function edit($id)
    {
        $ad = Advertisement::with('media', 'kiosks')->findOrFail($id);
        $kiosks = \App\Models\Kiosk::all();
        $selectedKiosks = $ad->kiosks->pluck('id')->toArray();
        return view('backend.layouts.advertisement.edit', compact('ad', 'kiosks', 'selectedKiosks'));
    }

    public function update(Request $request, $id)
    {
        $ad = Advertisement::findOrFail($id);
        $request->validate([
            'title' => 'required|string|max:255',
            'status' => 'required|in:active,inactive',
            'media.*' => 'nullable|file|mimes:jpeg,png,jpg,webp,mp4,mov,avi|max:20480',
            'kiosk_ids' => 'nullable|array',
            'kiosk_ids.*' => 'exists:kiosks,id',
        ]);
        DB::beginTransaction();
        try {
            $ad->update($request->only(['title', 'status']));
            if ($request->hasFile('media')) {
                foreach ($request->file('media') as $file) {
                    $type = in_array($file->extension(), ['mp4', 'mov', 'avi']) ? 'video' : 'image';
                    $path = uploadImage($file, 'advertisements');
                    AdvertisementMedia::create([
                        'advertisement_id' => $ad->id,
                        'file_path' => $path,
                        'type' => $type,
                    ]);
                }
            }
            $ad->kiosks()->sync($request->input('kiosk_ids', []));
            DB::commit();
            return redirect()->route('admin.advertisements.index')->with('t-success', 'Advertisement updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('t-error', $e->getMessage());
        }
    }

    public function destroy($id)
    {
        $ad = Advertisement::findOrFail($id);
        foreach ($ad->media as $media) {
            if ($media->file_path && file_exists(public_path($media->file_path))) {
                unlink(public_path($media->file_path));
            }
            $media->delete();
        }
        $ad->delete();
        return response()->json(['success' => true, 'message' => 'Advertisement deleted successfully.']);
    }

    public function destroyMedia($id)
    {
        $media = AdvertisementMedia::findOrFail($id);
        if ($media->file_path && file_exists(public_path($media->file_path))) {
            unlink(public_path($media->file_path));
        }
        $media->delete();
        return response()->json(['success' => true, 'message' => 'Media deleted successfully.']);
    }
} 