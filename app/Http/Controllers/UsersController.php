<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\RedirectResponse;
use App\Http\Requests\UserUpdateRequest;
use Yajra\DataTables\Facades\DataTables;

class UsersController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $currentUserId = Auth::id();
            $users = User::select(['id', 'name', 'email', 'status', 'created_at'])->where('id', '!=', $currentUserId);

            return Datatables::of($users)
                ->addIndexColumn()
                ->addColumn('dateAdded', function ($row) {
                    $dateAdded = \Carbon\Carbon::parse($row->created_at);
                    return '<span class="">' . date("d-m-Y", strtotime($dateAdded)) . '</span>';
                    // '<br><span class="text-muted">' . date("g:i A", strtotime($dateAdded)) . '</span>';
                })
                ->addColumn('role', function ($row) {
                    return ucfirst($row->roles[0]->name);
                })
                ->addColumn('status', function ($row) {
                    if ($row->status == 'approved') {
                        return '<span class="badge bg-success">Approved</span>';
                    } else {
                        return '<span class="badge bg-danger">Pending</span>';
                    }
                })
                ->addColumn('actions', function ($row) {
                    $settingsButton = '<a href="' . route('users.setting', $row->id) . '" class="btn btn-sm btn-alt-secondary" data-bs-toggle="tooltip" title="Settings">
                                        <i class="si si-settings"></i>
                                    </a>';
        
                    $deleteButton = '<a href="#" class="btn btn-sm btn-alt-secondary delete-user" data-bs-toggle="tooltip" data-id="'.$row->id.'" title="Delete">
                                        <i class="fa fa-times"></i>
                                    </a>';
        
                    $settingsButton = Gate::check('user.edit') ? $settingsButton : '';
                    $deleteButton = Gate::check('user.delete') ? $deleteButton : '';
        
                    return '<div class="btn-group">' . $settingsButton . $deleteButton . '</div>';
                })
                ->rawColumns(['dateAdded', 'status', 'actions'])
                ->make(true);
        }

        return view('users.index');
    }

    public function create()
    {
        $roles = Role::get();
        return view('users.create', [
            'roles' => $roles
        ]);
    }

    public function store(Request $request)
    {
        // Validate the incoming request data
        $validatedData = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'phone_number' => 'nullable|string|max:20',
            'status' => 'required|in:pending,approved',
            'role' => 'required|string|exists:roles,name',
        ]);

        // Create the user
        $user = new User();
        $user->first_name = $validatedData['first_name'];
        $user->last_name = $validatedData['last_name'];
        $user->name = $validatedData['first_name'] . ' ' . $validatedData['last_name'];
        $user->email = $validatedData['email'];
        $user->password = Hash::make($validatedData['password']);
        $user->phone_number = $validatedData['phone_number'];
        $user->status = $validatedData['status'];
        $user->save();
        $user->syncRoles($request->role);

        // Redirect or return a response as needed
        return redirect()->route('users.index')->with('success', 'User created successfully!');
    }

    public function setting(User $user)
    {
        $roles = Role::all();

        return view('users.edit', [
            'user' => $user,
            'roles' => $roles,
        ]);
    }

    public function update(UserUpdateRequest $request, User $user): RedirectResponse
    {
        $validatedData = $request->validated();

        $validatedData['name'] = $validatedData['first_name'] . ' ' . $validatedData['last_name'];
        $user->update($validatedData);

        $user->syncRoles($request->role);

        // Redirect the user back to the appropriate page with a success message
        return redirect()->route('users.setting', $user)->with('success', 'User updated successfully');
    }

    public function updatePassword(Request $request, User $user): RedirectResponse
    {
        $validated = $request->validate([
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user->update([
            'password' => Hash::make($validated['password']),
        ]);

        return back()->with('status', 'password-updated');
    }

    public function destroy(User $user)
    {
        // Check if the authenticated user can delete the user (you can add authorization logic here)

        // Delete the user
        $user->delete();

        // Return the user back to the appropriate page with a success message
        return response()->json(['message' => 'User deleted successfully']);
    }
}
