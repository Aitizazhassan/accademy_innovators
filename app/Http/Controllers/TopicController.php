<?php

namespace App\Http\Controllers;

use App\Http\Requests\TopicRequest;
use App\Models\Classroom;
use App\Models\Chapter;
use App\Models\Subject;
use App\Models\Topic;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;

class TopicController extends Controller
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
            $classroom = Topic::get();
            return Datatables::of($classroom)
                ->addIndexColumn()
                ->addColumn('dateAdded', function ($row) {
                    $dateAdded = \Carbon\Carbon::parse($row->created_at);
                    return '<span class="">' . date("d-m-Y", strtotime($dateAdded)) . '</span>';
                    // '<br><span class="text-muted">' . date("g:i A", strtotime($dateAdded)) . '</span>';
                })
                ->addColumn('actions', function ($row) {
                    $settingsButton = '<a href="' . route('topic.edit', $row->id) . '" class="btn btn-sm btn-alt-secondary" data-bs-toggle="tooltip" title="Edit">
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
        $pageHead = 'Topic';
        $pageTitle = 'Topic';
        $activeMenu = 'Topic';
        return view('topics.index', compact('activeMenu','pageHead','pageTitle'));
    }
    public function create()
    {

        $pageHead = 'Create topic';
        $pageTitle = 'Create topic';
        $activeMenu = 'create_topic';

        $topic = Chapter::get();

        return view('topics.create', compact('activeMenu','pageHead','pageTitle','topic'));

    }

    public function store(TopicRequest $request)
    {

        $validatedData = $request->validate([
            'name' => 'required|string',
            'chapter_id' => 'required|array',
        ]);


        // Validate the incoming request data
        $validatedData = $request->validated();

        // Check if a session with the same name already exists
        $existingSession = Topic::where('name', $validatedData['name'])->first();

        if ($existingSession) {
            // If a session with the same name already exists, return with error message
            return redirect()->back()->with('error', 'A Topic with the same name already exists.');
        }

        // Create a new session instance
        $chapter = new Topic();
        $chapter->name = $validatedData['name'];
        $chapter->save();

        // Attach campuses to the session
        $chapter->chapters()->attach($validatedData['chapter_id']);

        return redirect()->route('topic.index')->with('success', 'Topic created successfully.');

    }


    public function edit(Topic $topic)
{
    $pageHead = 'Edit Topic';
    $pageTitle = 'Edit Topic';
    $activeMenu = 'Topic';

    // Fetch all chapters to populate the dropdown
    $chapters = Chapter::all();

    return view('topics.edit', compact('activeMenu', 'pageHead', 'pageTitle', 'topic', 'chapters'));
}

    public function update(TopicRequest $request, Chapter $chapter)
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

    public function show(Topic $chapter)
    {

        abort(404);

    }



    public function destroy(Request $request, $id)
    {

        try {
            // Find the board by ID
            $board = Topic::findOrFail($id);

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

