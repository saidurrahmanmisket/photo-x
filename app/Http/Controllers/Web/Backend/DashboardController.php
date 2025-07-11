<?php

namespace App\Http\Controllers\Web\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Kiosk;
use App\Models\Frame;
use App\Models\Photo;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index() {
        // Get total users
        $totalUsers = User::count();

        // Get total kiosks
        $totalKiosks = Kiosk::count();

        // Get total frames
        $totalFrames = Frame::count();

        // Get total photos
        $totalPhotos = Photo::count();

        // Get photos for the last 7 days
        $photos = DB::table('photos')
            ->selectRaw('DATE(created_at) as date, COUNT(*) as total')
            ->where('created_at', '>=', now()->subDays(7))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        $labels = $photos->pluck('date')->map(fn($d) => date('Y-m-d', strtotime($d)))->toArray();
        $data = $photos->pluck('total')->map(fn($count) => (int)$count)->toArray();

        return view('backend.layouts.index', compact(
            'totalUsers',
            'totalKiosks',
            'totalFrames',
            'totalPhotos',
            'labels',
            'data'
        ));
    }
}
