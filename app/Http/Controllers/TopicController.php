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
use Illuminate\Support\Facades\Session;
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
            $topics = Topic::with('chapter', 'subject', 'classroom', 'classroom.boards', 'classroom.boards.countries')->orderBy('id', 'desc');
            return Datatables::of($topics)
                ->addIndexColumn()
                ->addColumn('dateAdded', function ($row) {
                    $dateAdded = \Carbon\Carbon::parse($row->created_at);
                    return '<span class="">' . date("d-m-Y", strtotime($dateAdded)) . '</span>';
                    // '<br><span class="text-muted">' . date("g:i A", strtotime($dateAdded)) . '</span>';
                })
                ->addColumn('topic_countries', function ($row) {
                    $countries = '';
                    $addedCountries = []; 
                    if ($row->classroom) {
                            if ($row->classroom->boards) {
                                foreach ($row->classroom->boards as $board) {
                                    if ($board->countries) {
                                        foreach ($board->countries as $country) {
                                            if (!in_array($country->name, $addedCountries)) {
                                                $countries .= '<span class="badge bg-primary">' . $country->name . '</span>&nbsp;';
                                                $addedCountries[] = $country->name;
                                            }
                                        }
                                    }
                                }
                            }
                    }
                    return $countries;
                })
                ->addColumn('topic_boards', function ($row) {
                    $boards = '';
                    $addedBoards = [];
                    if ($row->classroom) {
                            if ($row->classroom->boards) {
                                foreach ($row->classroom->boards as $board) {
                                    if (!in_array($board->name, $addedBoards)) {
                                        $boards .= '<span class="badge bg-primary">' . $board->name . '</span>&nbsp;';
                                        $addedBoards[] = $board->name;
                                    }
                                }
                            }
                    }
                    return $boards;
                })
                ->addColumn('topic_classroom', function ($row) {
                    $classroom = '';
                    if ($row->classroom) {
                        $classroom = $row->classroom->name;
                    }
                    return $classroom;
                })
                ->addColumn('topic_subject', function ($row) {
                    $subject = '';
                    if ($row->subject) {
                            $subject = $row->subject->name;
                    }
                    return $subject;
                })
                ->addColumn('topic_chapter', function ($row) {
                    $chapter = '';
                    if ($row->chapter) {
                            $chapter = $row->chapter->name;
                    }
                    return $chapter;
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
                ->rawColumns(['dateAdded', 'status', 'topic_countries', 'topic_boards', 'topic_classroom', 'topic_subject', 'topic_chapter', 'actions'])
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

        // $topic = Chapter::get();
        $classes = Classroom::get();

        return view('topics.create', compact('activeMenu','pageHead','pageTitle', 'classes'));

    }

    // public function store(TopicRequest $request)
    // {

    //     $validatedData = $request->validate([
    //         'name' => 'required|string',
    //         'chapter_id' => 'required|array',
    //     ]);


    //     // Validate the incoming request data
    //     $validatedData = $request->validated();
    //     // Check if a session with the same name already exists
    //     $existingSession = Topic::where('name', $validatedData['name'])->first();
    //     if ($existingSession) {
    //         // If a session with the same name already exists, return with error message
    //         return redirect()->back()->with('error', 'A Topic with the same name already exists.');
    //     }
    //     // Create a new session instance
    //     $chapter = new Topic();
    //     $chapter->name = $validatedData['name'];
    //     $chapter->save();

    //     // Attach campuses to the session
    //     $chapter->chapters()->attach($validatedData['chapter_id']);

    //     return redirect()->route('topic.index')->with('success', 'Topic created successfully.');

    // }
    public function store(TopicRequest $request)
{
    $validatedData = $request->validate([
        'class_id' => 'required',
        'subject_id' => 'required',
        'chapter_id' => 'required',
        'name.*' => 'required|string',
    ]);

    // foreach ($validatedData['name'] as $name) {
    //     // Check if a topic with the same name already exists
    //     $existingTopic = Topic::where('name', $name)->first();
    //     if ($existingTopic) {
    //         // If a topic with the same name already exists, return with error message
    //         return redirect()->back()->with('error', "A Topic with the name '{$name}' already exists.");
    //     }

    //     // Create a new topic instance
    //     $topic = new Topic();
    //     $topic->classroom_id =  $request->class_id;
    //     $topic->subject_id =  $request->subject_id;
    //     $topic->chapter_id =  $request->chapter_id;
    //     $topic->name = $name;
    //     $topic->save();

    //     // Attach chapters to the topic
    //     // $topic->chapters()->attach($validatedData['chapter_id']);
    // }

    foreach ($validatedData['class_id'] as $classId) {

        foreach ($validatedData['name'] as $name) {
            $existingTopic = Topic::where('name', $name)->where('classroom_id', $classId)->where('subject_id', $request->subject_id)->where('chapter_id', $request->chapter_id)->first();
            if ($existingTopic) {
                Session::flash('error', "A Topic with the name '{$name}' against such class and subject already exists.");
            } else {

                // Create a new topic instance
                $topic = new Topic();
                $topic->classroom_id =  $classId;
                $topic->subject_id =  $request->subject_id;
                $topic->chapter_id =  $request->chapter_id;
                $topic->name =  $name;
                $topic->save();

                // Attach campuses to the session
                // $chapter->subjects()->attach($validatedData['subject_id']);
                $message = 'Topics created successfully.';
                Session::flash('success', $message);
            }
        }
    }

    return redirect()->route('topic.index');
}



    public function edit(Topic $topic)
{
    $topic = Topic::with('classroom', 'classroom.subjects', 'subject', 'subject.chapters')->find($topic->id);
    $pageHead = 'Edit Topic';
    $pageTitle = 'Edit Topic';
    $activeMenu = 'Topic';
    // Fetch all chapters to populate the dropdown
    $classes = Classroom::get();
    $subjects = $topic->classroom->subjects;
    $chapters = $topic->subject->chapters;

    return view('topics.edit', compact('activeMenu', 'pageHead', 'pageTitle', 'topic', 'classes', 'subjects', 'chapters'));
}

    public function update(TopicRequest $request, Topic $topic)
    {

        $validatedData = $request->validate([
            'class_id' => 'required',
            'subject_id' => 'required',
            'chapter_id' => 'required',
            'name.*' => 'required|string',
        ]);
         // Validate the incoming request data
         $validatedData = $request->validated();

         // Update session name
         $topic->name = $validatedData['name'];
         $topic->classroom_id =  $request->class_id;
         $topic->subject_id =  $request->subject_id;
         $topic->chapter_id =  $request->chapter_id;
         $topic->save();

         // Sync campuses for the session
        //  $topic->chapters()->sync($validatedData['chapter_id']);

        return redirect()->route('topic.index')->with('success', 'Topic updated successfully.');

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

