<?php

namespace App\Http\Controllers\Web\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;
use Illuminate\Http\JsonResponse;

class UserController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $users = User::query();
            
            if (!empty($request->input('search.value'))) {
                $searchTerm = $request->input('search.value');
                $users->where(function($query) use ($searchTerm) {
                    $query->where('name', 'LIKE', "%$searchTerm%")
                          ->orWhere('email', 'LIKE', "%$searchTerm%");
                });
            }

            return DataTables::of($users)
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
                ->addColumn('role', function ($row) {
                    return ucfirst($row->role ?? 'user');
                })
                ->addColumn('created_at', function ($row) {
                    return $row->created_at ? $row->created_at->format('Y-m-d H:i') : '-';
                })
                ->addColumn('action', function ($row) {
                    return '<div class="text-center"><div class="btn-group btn-group-sm" role="group" aria-label="Basic example">
                              <a href="' . route('admin.users.edit', ['id' => $row->id]) . '" class="text-white btn btn-primary" title="Edit">
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
        return view('backend.layouts.user.index');
    }

    public function create()
    {
        return view('backend.layouts.user.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:admin,user',
        ]);

        try {
            User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => $request->role,
                'email_verified_at' => now(),
            ]);

            return redirect()->route('admin.users.index')->with('t-success', 'User created successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('t-error', $e->getMessage());
        }
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);
        return view('backend.layouts.user.edit', compact('user'));
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);
        
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:8|confirmed',
            'role' => 'required|in:admin,user',
        ]);

        try {
            $data = [
                'name' => $request->name,
                'email' => $request->email,
                'role' => $request->role,
            ];

            if ($request->filled('password')) {
                $data['password'] = Hash::make($request->password);
            }

            $user->update($data);

            return redirect()->route('admin.users.index')->with('t-success', 'User updated successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('t-error', $e->getMessage());
        }
    }

    public function status($id): JsonResponse
    {
        $user = User::findOrFail($id);
        
        if ($user->email_verified_at) {
            $user->email_verified_at = null;
            $user->save();

            return response()->json([
                'success' => false,
                'message' => 'User deactivated successfully.',
                'data' => $user,
            ]);
        } else {
            $user->email_verified_at = now();
            $user->save();

            return response()->json([
                'success' => true,
                'message' => 'User activated successfully.',
                'data' => $user,
            ]);
        }
    }

    public function destroy($id): JsonResponse
    {
        try {
            $user = User::findOrFail($id);
            
            // Delete user's avatar if exists
            if ($user->avatar && file_exists(public_path($user->avatar))) {
                unlink(public_path($user->avatar));
            }
            
            $user->delete();
            
            return response()->json([
                'success' => true,
                'message' => 'User deleted successfully.',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete user.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
} 