<?php

namespace App\Http\Controllers;

use App\Models\Board;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\RedirectResponse;
use App\Http\Requests\BoardUpdateRequest;
use App\Models\Country;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class BoardController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $currentUserId = Auth::id();
            $boards = Board::select(['id', 'name', 'created_at'])->where('id', '!=', $currentUserId);

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

        return view('boards.index');
    }

    public function create()
    {

        $pageHead = 'Create Board';
        $pageTitle = 'Create Board';
        $activeMenu = 'create_Board';

        $countries  = Country::get();

        return view('boards.create', compact('activeMenu', 'pageHead', 'pageTitle', 'countries'));
    }


    public function store(Request $request)
    {
        // Validate the incoming request data
        $validatedData = $request->validate([
            'name.*' => 'required|string',
            'country_id' => 'required|array', // Ensure country_id is an array
            'country_id.*' => 'exists:countries,id', // Ensure each country_id exists
        ]);

        foreach ($validatedData['name'] as $name) {
            // Check if a topic with the same name already exists
            $existingTopic = Board::where('name', $name)->first();
            if ($existingTopic) {
                // If a topic with the same name already exists, return with error message
                return redirect()->back()->with('error', "A board with the name '{$name}' already exists.");
            }

            // Create a new topic instance
            $board = new Board();
            $board->name = $name;
            $board->save();


            // Attach chapters to the topic
            $board->countries()->attach($validatedData['country_id']);
        }

        return redirect()->route('boards.index')->with('success', 'Board created successfully!');
    }



    // public function edit($id)
    // {
    //     $board = Board::find($id);
    //     return view('boards.edit', compact('board'));
    // }
    public function edit(Board $board)
    {

        $pageHead = 'Edit Board';
        $pageTitle = 'Edit Board';
        $activeMenu = 'Board';

        $countries = Country::get();

        return view('boards.edit', compact('activeMenu', 'pageHead', 'pageTitle', 'board', 'countries'));
    }

    public function update(BoardUpdateRequest $request, Board $board): RedirectResponse
    {
        // Validate the incoming request data
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'country_id' => 'required|array', // Ensure country_id is an array
            'country_id.*' => 'exists:countries,id', // Ensure each country_id exists
        ]);

        // Update the board's name
        $board->update(['name' => $validatedData['name']]);

        // Sync the associated countries
        $board->countries()->sync($validatedData['country_id']);

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
            $board = Board::findOrFail($id);

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
