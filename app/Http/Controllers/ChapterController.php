<?php

namespace App\Http\Controllers;

use App\Http\Requests\ChapterRequest;
use App\Models\Classroom;
use App\Models\Chapter;
use App\Models\Subject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;

class ChapterController extends Controller
{

    public $classSubjects;

    // public function __construct()
    // {

    //     $this->classSubjects = Subject::classRoomSubjects()->get();

    // }


    public function index(Request $request)
    {
        if ($request->ajax()) {
            $currentUserId = Auth::id();
            //$classroom = Classroom::select(['id', 'name', 'created_at']);
            $classroom = Chapter::with('subject')->get();
            return Datatables::of($classroom)
                ->addIndexColumn()
                ->addColumn('dateAdded', function ($row) {
                    $dateAdded = \Carbon\Carbon::parse($row->created_at);
                    return '<span class="">' . date("d-m-Y", strtotime($dateAdded)) . '</span>';
                    // '<br><span class="text-muted">' . date("g:i A", strtotime($dateAdded)) . '</span>';
                })

                ->addColumn('actions', function ($row) {

                    $settingsButton = '<a href="' . route('chapter.edit', $row->id) . '" class="btn btn-sm btn-alt-secondary" data-bs-toggle="tooltip" title="Edit">
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
        $pageHead = 'Chapter';
        $pageTitle = 'Chapter';
        $activeMenu = 'chapter';
        return view('chapter.index', compact('activeMenu','pageHead','pageTitle'));



    }

    public function create()
    {

        $pageHead = 'Create Chapter';
        $pageTitle = 'Create Chapter';
        $activeMenu = 'create_chapter';

        $subjects = Subject::get();

        return view('chapter.create', compact('activeMenu','pageHead','pageTitle','subjects'));

    }

    public function store(ChapterRequest $request)
    {

        $validatedData = $request->validate([
            'name' => 'required|string',
            'subject_id' => 'required|array',
        ]);


        // Validate the incoming request data
        $validatedData = $request->validated();

        // Check if a session with the same name already exists
        $existingSession = Chapter::where('name', $validatedData['name'])->first();

        if ($existingSession) {
            // If a session with the same name already exists, return with error message
            return redirect()->back()->with('error', 'A chapter with the same name already exists.');
        }

        // Create a new session instance
        $chapter = new Chapter();
        $chapter->name = $validatedData['name'];
        $chapter->save();

        // Attach campuses to the session
        $chapter->subjects()->attach($validatedData['subject_id']);

        return redirect()->route('chapter.index')->with('success', 'Chapter created successfully.');

    }


    public function edit(Chapter $chapter)
    {

        $pageHead = 'Edit Chapter';
        $pageTitle = 'Edit Chapter';
        $activeMenu = 'chapter';

        $subjects = Subject::get();

        return view('chapter.edit', compact('activeMenu','pageHead','pageTitle','chapter','subjects'));

    }

    public function update(ChapterRequest $request, Chapter $chapter)
    {

        $validatedData = $request->validate([
            'name' => 'required|string',
            'subject_id' => 'required|array',
        ]);
         // Validate the incoming request data
         $validatedData = $request->validated();

         // Update session name
         $chapter->name = $validatedData['name'];
         $chapter->save();

         // Sync campuses for the session
         $chapter->subjects()->sync($validatedData['subject_id']);

        return redirect()->route('chapter.index')->with('success', 'Chapter updated successfully.');

    }

    public function show(Chapter $chapter)
    {

        abort(404);

    }



    public function destroy(Request $request, $id)
    {

        try {
            // Find the board by ID
            $board = Chapter::findOrFail($id);

            // Delete the board
            $board->delete();

            // Return a JSON response indicating success
            return response()->json(['message' => 'Chapter deleted successfully.']);
        } catch (ModelNotFoundException $ex) {
            // If the board doesn't exist, return a 404 Not Found response
            return response()->json(['error' => 'Chapter not found'], 404);
        } catch (Exception $ex) {
            // Return a 500 Internal Server Error response if an error occurs
            return response()->json(['error' => 'Internal Server Error'], 500);
        }
    }


}

