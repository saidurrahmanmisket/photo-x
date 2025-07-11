<?php

namespace App\Http\Controllers\Web\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Booking;
use App\Models\Kiosk;
use App\Models\Customer;
use App\Models\BookingSetting;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;
use Illuminate\Http\JsonResponse;

class BookingController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $bookings = Booking::with(['user', 'kiosk'])->select('bookings.*');
            
            if (!empty($request->input('search.value'))) {
                $searchTerm = $request->input('search.value');
                $bookings->whereHas('user', function($query) use ($searchTerm) {
                    $query->where('name', 'LIKE', "%$searchTerm%")
                          ->orWhere('email', 'LIKE', "%$searchTerm%");
                })->orWhereHas('kiosk', function($query) use ($searchTerm) {
                    $query->where('name', 'LIKE', "%$searchTerm%");
                });
            }

            return DataTables::of($bookings)
                ->addIndexColumn()
                ->addColumn('user', function ($row) {
                    return $row->user ? $row->user->name : '-';
                })
                ->addColumn('kiosk', function ($row) {
                    return $row->kiosk ? $row->kiosk->name : '-';
                })
                ->addColumn('payment_type', function ($row) {
                    $badge = $row->payment_type === 'vendor' ? 'badge bg-warning' : 'badge bg-info';
                    return '<span class="' . $badge . '">' . ucfirst($row->payment_type) . '</span>';
                })
                ->addColumn('amount', function ($row) {
                    return '$' . number_format($row->amount, 2);
                })
                ->addColumn('print_info', function ($row) {
                    if ($row->payment_type === 'vendor') {
                        return $row->prints_used . '/' . $row->print_limit;
                    }
                    return 'Unlimited';
                })
                ->addColumn('status', function ($row) {
                    $statusColors = [
                        'pending' => 'bg-warning',
                        'paid' => 'bg-info',
                        'active' => 'bg-success',
                        'expired' => 'bg-danger',
                        'cancelled' => 'bg-secondary'
                    ];
                    $color = $statusColors[$row->status] ?? 'bg-secondary';
                    return '<span class="badge ' . $color . '">' . ucfirst($row->status) . '</span>';
                })
                ->addColumn('created_at', function ($row) {
                    return $row->created_at ? $row->created_at->diffForHumans() : '-';
                })
                ->addColumn('action', function ($row) {
                    $showUrl = route('admin.bookings.show', $row->id);
                    $editUrl = route('admin.bookings.edit', $row->id);
                    $deleteBtn = "<a href='#' onclick='showDeleteConfirm($row->id)' class='btn btn-danger btn-sm'>Delete</a>";
                    $showBtn = "<a href='$showUrl' class='btn btn-info btn-sm'>View</a>";
                    $editBtn = "<a href='$editUrl' class='btn btn-primary btn-sm'>Edit</a>";
                    return "$showBtn $editBtn $deleteBtn";
                })
                ->rawColumns(['payment_type', 'status', 'action'])
                ->make(true);
        }
        return view('backend.layouts.booking.index');
    }

    public function create()
    {
        $customers = Customer::where('email_verified_at', '!=', null)->get();
        $kiosks = Kiosk::where('status', 'active')->get();
        $vendorLimit = BookingSetting::getValue('vendor_print_limit', 10);
        
        return view('backend.layouts.booking.create', compact('customers', 'kiosks', 'vendorLimit'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:customers,id',
            'kiosk_id' => 'required|exists:kiosks,id',
            'payment_type' => 'required|in:vendor,user',
            'amount' => 'required|numeric|min:0',
            'print_limit' => 'nullable|integer|min:1',
            'expires_at' => 'nullable|date|after:now',
            'notes' => 'nullable|string',
        ]);

        try {
            $data = $request->only(['user_id', 'kiosk_id', 'payment_type', 'amount', 'notes']);
            
            if ($request->payment_type === 'vendor') {
                $data['print_limit'] = $request->print_limit ?? BookingSetting::getValue('vendor_print_limit', 10);
            }
            
            if ($request->filled('expires_at')) {
                $data['expires_at'] = $request->expires_at;
            }

            $data['status'] = 'active';
            
            Booking::create($data);

            return redirect()->route('admin.bookings.index')->with('t-success', 'Booking created successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('t-error', $e->getMessage());
        }
    }

    public function show($id)
    {
        $booking = Booking::with(['user', 'kiosk', 'payments', 'photos'])->findOrFail($id);
        return view('backend.layouts.booking.show', compact('booking'));
    }

    public function edit($id)
    {
        $booking = Booking::findOrFail($id);
        $customers = Customer::where('email_verified_at', '!=', null)->get();
        $kiosks = Kiosk::where('status', 'active')->get();
        $vendorLimit = BookingSetting::getValue('vendor_print_limit', 10);
        
        return view('backend.layouts.booking.edit', compact('booking', 'customers', 'kiosks', 'vendorLimit'));
    }

    public function update(Request $request, $id)
    {
        $booking = Booking::findOrFail($id);
        
        $request->validate([
            'user_id' => 'required|exists:customers,id',
            'kiosk_id' => 'required|exists:kiosks,id',
            'payment_type' => 'required|in:vendor,user',
            'amount' => 'required|numeric|min:0',
            'print_limit' => 'nullable|integer|min:1',
            'expires_at' => 'nullable|date',
            'status' => 'required|in:pending,paid,active,expired,cancelled',
            'notes' => 'nullable|string',
        ]);

        try {
            $data = $request->only(['user_id', 'kiosk_id', 'payment_type', 'amount', 'status', 'notes']);
            
            if ($request->payment_type === 'vendor') {
                $data['print_limit'] = $request->print_limit ?? BookingSetting::getValue('vendor_print_limit', 10);
            } else {
                $data['print_limit'] = null;
            }
            
            if ($request->filled('expires_at')) {
                $data['expires_at'] = $request->expires_at;
            } else {
                $data['expires_at'] = null;
            }

            $booking->update($data);

            return redirect()->route('admin.bookings.index')->with('t-success', 'Booking updated successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('t-error', $e->getMessage());
        }
    }

    public function destroy($id): JsonResponse
    {
        try {
            $booking = Booking::findOrFail($id);
            $booking->delete();
            
            return response()->json([
                'success' => true,
                'message' => 'Booking deleted successfully.',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete booking.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function settings()
    {
        $settings = [
            'vendor_print_limit' => BookingSetting::getValue('vendor_print_limit', 10),
            'default_booking_duration' => BookingSetting::getValue('default_booking_duration', 24), // hours
            'max_prints_per_booking' => BookingSetting::getValue('max_prints_per_booking', 50),
        ];
        
        return view('backend.layouts.booking.settings', compact('settings'));
    }

    public function updateSettings(Request $request)
    {
        $request->validate([
            'vendor_print_limit' => 'required|integer|min:1|max:100',
            'default_booking_duration' => 'required|integer|min:1|max:168', // max 7 days
            'max_prints_per_booking' => 'required|integer|min:1|max:1000',
        ]);

        try {
            BookingSetting::setValue('vendor_print_limit', $request->vendor_print_limit, 'Default print limit for vendor bookings');
            BookingSetting::setValue('default_booking_duration', $request->default_booking_duration, 'Default booking duration in hours');
            BookingSetting::setValue('max_prints_per_booking', $request->max_prints_per_booking, 'Maximum prints allowed per booking');

            return redirect()->back()->with('t-success', 'Booking settings updated successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('t-error', $e->getMessage());
        }
    }
} 