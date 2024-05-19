<?php

namespace App\Http\Controllers;

use App\Http\Requests\ClassRoomRequest;
use App\Models\Board;
use App\Models\Classroom;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;

class ClassRoomController extends Controller
{

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $currentUserId = Auth::id();
            $classroom = Classroom::select(['id', 'name', 'created_at']);

            return Datatables::of($classroom)
                ->addIndexColumn()
                ->addColumn('dateAdded', function ($row) {
                    $dateAdded = \Carbon\Carbon::parse($row->created_at);
                    return '<span class="">' . date("d-m-Y", strtotime($dateAdded)) . '</span>';
                    // '<br><span class="text-muted">' . date("g:i A", strtotime($dateAdded)) . '</span>';
                })

                ->addColumn('actions', function ($row) {

                    $settingsButton = '<a href="' . route('classroom.edit', $row->id) . '" class="btn btn-sm btn-alt-secondary" data-bs-toggle="tooltip" title="Edit">
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

        return view('classroom.index');
    }


    public function create()
    {

        $pageHead = 'Create Class Room';
        $pageTitle = 'Create Class Room';
        $activeMenu = 'create_classroom';

        $boards = Board::get();

        return view('classroom.create', compact('activeMenu', 'pageHead', 'pageTitle', 'boards'));
    }


    public function store(ClassRoomRequest $request)
    {

        $validatedData = $request->validate([
            'name' => 'required|string',
            'board_id' => 'required|array',
        ]);

         // Validate the incoming request data
         $validatedData = $request->validated();

         // Check if a session with the same name already exists
         $existingSession = Classroom::where('name', $validatedData['name'])->first();

         if ($existingSession) {
             // If a session with the same name already exists, return with error message
             return redirect()->back()->with('error', 'A class with the same name already exists.');
         }

         // Create a new session instance
         $classroom = new Classroom();
         $classroom->name = $validatedData['name'];
         $classroom->save();

         // Attach campuses to the session
         $classroom->boards()->attach($validatedData['board_id']);

         // Redirect with success message
         return redirect()->route('classroom.index')->with('success', 'Class created successfully.');

    }



    public function edit(Classroom $classroom)
    {

        $pageHead = 'Edit Class';
        $pageTitle = 'Edit Class';
        $activeMenu = 'classroom';

        $boards = Board::get();

        return view('classroom.edit', compact('activeMenu', 'pageHead', 'pageTitle', 'classroom', 'boards'));
    }

    public function update(ClassRoomRequest $request, Classroom $classroom)
{
    // Validate the request data
    $validatedData = $request->validate([
        'name' => 'required|string',
        'board_id' => 'required|array',
    ]);

    // Update the classroom's name
    $classroom->update(['name' => $validatedData['name']]);

    // Sync the associated boards
    $classroom->boards()->sync($validatedData['board_id']);

    return redirect()->route('classroom.index')->with('success', 'Classroom updated successfully.');
}

    public function show(Classroom $classroom)
    {

        abort(404);
    }

    public function destroy(Request $request, $id)
    {

        try {
            // Find the board by ID
            $board = ClassRoom::findOrFail($id);

            // Delete the board
            $board->delete();

            // Return a JSON response indicating success
            return response()->json(['message' => 'Class Room deleted successfully']);
        } catch (ModelNotFoundException $ex) {
            // If the board doesn't exist, return a 404 Not Found response
            return response()->json(['error' => 'ClassRoom not found'], 404);
        } catch (Exception $ex) {
            // Return a 500 Internal Server Error response if an error occurs
            return response()->json(['error' => 'Internal Server Error'], 500);
        }
    }
}
