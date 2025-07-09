<?php

namespace App\Http\Controllers\Web\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Customer;
use Yajra\DataTables\DataTables;
use Illuminate\Http\JsonResponse;

class CustomerController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $customers = Customer::query();
            
            if (!empty($request->input('search.value'))) {
                $searchTerm = $request->input('search.value');
                $customers->where(function($query) use ($searchTerm) {
                    $query->where('name', 'LIKE', "%$searchTerm%")
                          ->orWhere('email', 'LIKE', "%$searchTerm%");
                });
            }

            return DataTables::of($customers)
                ->addIndexColumn()
                ->addColumn('avatar', function ($row) {
                    return $row->avatar ? '<img src="' . asset($row->avatar) . '" style="max-width:40px; border-radius:50%;">' : '<div class="avatar-placeholder">' . strtoupper(substr($row->name, 0, 1)) . '</div>';
                })
                ->addColumn('status', function ($row) {
                    $status = '<div class="form-check form-switch">';
                    $status .= '<input onclick="showStatusChangeAlert(' . $row->id . ')" type="checkbox" class="form-check-input" id="customSwitch' . $row->id . '" getAreaid="' . $row->id . '" name="status"';
                    if ($row->email_verified_at) {
                        $status .= "checked";
                    }
                    $status .= '><label for="customSwitch' . $row->id . '" class="form-check-label" for="customSwitch"></label></div>';
                    return $status;
                })
                ->addColumn('created_at', function ($row) {
                    return $row->created_at ? $row->created_at->format('Y-m-d H:i') : '-';
                })
                ->addColumn('action', function ($row) {
                    return '<div class="text-center"><div class="btn-group btn-group-sm" role="group" aria-label="Basic example">
                              <a href="' . route('admin.customers.edit', ['id' => $row->id]) . '" class="text-white btn btn-primary" title="Edit">
                              <i class="bi bi-pencil"></i>
                              </a>
                              <a href="#" onclick="showDeleteConfirm(' . $row->id . ')" type="button" class="text-white btn btn-danger" title="Delete">
                              <i class="bi bi-trash"></i>
                            </a>
                            </div></div>';
                })
                ->rawColumns(['avatar', 'status', 'action'])
                ->make(true);
        }
        return view('backend.layouts.customer.index');
    }

    public function create()
    {
        return view('backend.layouts.customer.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:customers,email',
        ]);

        try {
            Customer::create([
                'name' => $request->name,
                'email' => $request->email,
                'email_verified_at' => now(),
            ]);

            return redirect()->route('admin.customers.index')->with('t-success', 'Customer created successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('t-error', $e->getMessage());
        }
    }

    public function edit($id)
    {
        $customer = Customer::findOrFail($id);
        return view('backend.layouts.customer.edit', compact('customer'));
    }

    public function update(Request $request, $id)
    {
        $customer = Customer::findOrFail($id);
        
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:customers,email,' . $customer->id,
        ]);

        try {
            $customer->update([
                'name' => $request->name,
                'email' => $request->email,
            ]);

            return redirect()->route('admin.customers.index')->with('t-success', 'Customer updated successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('t-error', $e->getMessage());
        }
    }

    public function status($id): JsonResponse
    {
        $customer = Customer::findOrFail($id);
        
        if ($customer->email_verified_at) {
            $customer->email_verified_at = null;
            $customer->save();

            return response()->json([
                'success' => false,
                'message' => 'Customer deactivated successfully.',
                'data' => $customer,
            ]);
        } else {
            $customer->email_verified_at = now();
            $customer->save();

            return response()->json([
                'success' => true,
                'message' => 'Customer activated successfully.',
                'data' => $customer,
            ]);
        }
    }

    public function destroy($id): JsonResponse
    {
        try {
            $customer = Customer::findOrFail($id);
            
            // Delete customer's avatar if exists
            if ($customer->avatar && file_exists(public_path($customer->avatar))) {
                unlink(public_path($customer->avatar));
            }
            
            $customer->delete();
            
            return response()->json([
                'success' => true,
                'message' => 'Customer deleted successfully.',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete customer.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
} 