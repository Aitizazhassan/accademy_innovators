<?php

namespace App\Http\Controllers;

use App\Models\Country;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\RedirectResponse;
use App\Http\Requests\CountryUpdateRequest;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class CountryController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $currentUserId = Auth::id();
            $boards = Country::select(['id', 'name', 'created_at'])->where('id', '!=', $currentUserId);

            return Datatables::of($boards)
                ->addIndexColumn()
                ->addColumn('dateAdded', function ($row) {
                    $dateAdded = \Carbon\Carbon::parse($row->created_at);
                    return '<span class="">' . date("d-m-Y", strtotime($dateAdded)) . '</span>';
                    // '<br><span class="text-muted">' . date("g:i A", strtotime($dateAdded)) . '</span>';
                })

                ->addColumn('actions', function ($row) {

                    $settingsButton = '<a href="' . route('boards.edit', $row->id) . '" class="btn btn-sm btn-alt-secondary" data-bs-toggle="tooltip" title="Edit">
                                    <i class="fa fa-pencil-alt"></i>
                                </a>';

                    $deleteButton = '<a href="#" class="btn btn-sm btn-alt-secondary delete-user" data-bs-toggle="tooltip" data-id="' . $row->id . '" title="Delete">
                                         <i class="fa fa-times"></i>
                                     </a>';

                    $settingsButton = Gate::check('user.edit') ? $settingsButton : '';
                    $deleteButton = Gate::check('user.delete') ? $deleteButton : '';

                    return '<div class="btn-group">' . $settingsButton . $deleteButton . '</div>';
                })
                ->rawColumns(['dateAdded', 'status', 'actions'])
                ->make(true);
        }

        return view('country.index');
    }

    public function create()
    {

        return view('country.create');
    }

    public function store(Request $request)
    {
        // Validate the incoming request data
        $validatedData = $request->validate([
            'name' => 'required|string|max:255', // Change 'first_name' to 'name'
        ]);

        // Check if a board with the same name already exists
        $existingBoard = Country::where('name', $validatedData['name'])->first();

        // If a board with the same name already exists, show a message
        if ($existingBoard) {
            return redirect()->back()->withInput()->withErrors(['name' => 'This board already exists.']);
        }

        // Create the board
        $board = new Board();
        $board->name = $validatedData['name'];

        $board->save();

        // Redirect or return a response as needed
        return redirect()->route('boards.index')->with('success', 'Board created successfully!');
    }


    public function edit($id)
    {
        $board = Country::find($id);
        return view('country.edit', compact('board'));
    }

    public function update(CountryUpdateRequest $request, Board $board): RedirectResponse
    {
        $validatedData = $request->validate([
            'name' => 'required|string',
            // 'board_id' => 'required|array',
        ]);

        // Update the classroom's name
        $board->update(['name' => $validatedData['name']]);

        // Sync the associated boards
        // $board->boards()->sync($validatedData['board_id']);

        return redirect()->route('boards.index')->with('success', 'Board updated successfully.');


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

    public function destroy(Request $request, $id)
    {
        // dd($id);
        try {
            // Find the board by ID
            $board = Country::findOrFail($id);

            // Delete the board
            $board->delete();

            // Return a JSON response indicating success
            return response()->json(['message' => 'Board deleted successfully']);
        } catch (ModelNotFoundException $ex) {
            // If the board doesn't exist, return a 404 Not Found response
            return response()->json(['error' => 'Board not found'], 404);
        } catch (Exception $ex) {
            // Return a 500 Internal Server Error response if an error occurs
            return response()->json(['error' => 'Internal Server Error'], 500);
        }
    }
}
