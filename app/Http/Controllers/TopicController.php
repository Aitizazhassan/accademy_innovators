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
            $topics = Topic::with('chapters', 'subjects', 'classes', 'classes.boards', 'classes.boards.countries')
                ->orderBy('id', 'desc');
        
            return Datatables::of($topics)
                ->addIndexColumn()
                ->addColumn('dateAdded', function ($row) {
                    return '<span class="">' . $row->created_at->format('d-m-Y') . '</span>';
                })
                ->addColumn('topic_countries', function ($row) {
                    $countries = '';
                    $addedCountries = [];
        
                    foreach ($row->classes as $class) {
                        foreach ($class->boards as $board) {
                            foreach ($board->countries as $country) {
                                if (!in_array($country->name, $addedCountries)) {
                                    $countries .= '<span class="badge bg-primary">' . $country->name . '</span>&nbsp;';
                                    $addedCountries[] = $country->name;
                                }
                            }
                        }
                    }
                    return $countries;
                })
                ->addColumn('topic_boards', function ($row) {
                    $boards = '';
                    $addedBoards = [];
        
                    foreach ($row->classes as $class) {
                        foreach ($class->boards as $board) {
                            if (!in_array($board->name, $addedBoards)) {
                                $boards .= '<span class="badge bg-primary">' . $board->name . '</span>&nbsp;';
                                $addedBoards[] = $board->name;
                            }
                        }
                    }
                    return $boards;
                })
                ->addColumn('topic_classes', function ($row) {
                    $classes = '';
                    foreach ($row->classes as $class) {
                        $classes .= '<span class="badge bg-info">' . $class->name . '</span>&nbsp;';
                    }
                    return $classes;
                })
                ->addColumn('topic_subjects', function ($row) {
                    $subjects = '';
                    foreach ($row->subjects as $subject) {
                        $subjects .= '<span class="badge bg-warning">' . $subject->name . '</span>&nbsp;';
                    }
                    return $subjects;
                })
                ->addColumn('topic_chapters', function ($row) {
                    $chapters = '';
                    foreach ($row->chapters as $chapter) {
                        $chapters .= '<span class="badge bg-secondary">' . $chapter->name . '</span>&nbsp;';
                    }
                    return $chapters;
                })
                ->addColumn('actions', function ($row) {
                    $settingsButton = '<a href="' . route('topic.edit', $row->id) . '" class="btn btn-sm btn-alt-secondary" data-bs-toggle="tooltip" title="Edit">
                                          <i class="fa fa-pencil-alt"></i>
                                      </a>';
        
                    $deleteButton = '<a href="#" class="btn btn-sm btn-alt-secondary delete-topic" data-bs-toggle="tooltip" data-id="' . $row->id . '" title="Delete">
                                      <i class="fa fa-times"></i>
                                    </a>';
        
                    return '<div class="btn-group">' . $settingsButton . $deleteButton . '</div>';
                })
                ->rawColumns(['dateAdded', 'topic_countries', 'topic_boards', 'topic_classes', 'topic_subjects', 'topic_chapters', 'actions'])
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
            'class_id' => 'required|array',
            'subject_id' => 'required|array',
            'chapter_id' => 'required|array',
            'name.*' => 'required|string|max:255',
        ]);

        // Loop through each topic name
        foreach ($validatedData['name'] as $name) {
            // Check if the topic exists
            $existingTopic = Topic::where('name', $name)->first();

            if ($existingTopic) {
                // If topic exists, sync the class, subject, and chapter relationships
                $existingTopic->classes()->syncWithoutDetaching($validatedData['class_id']);
                $existingTopic->subjects()->syncWithoutDetaching($validatedData['subject_id']);
                $existingTopic->chapters()->syncWithoutDetaching($validatedData['chapter_id']);
            } else {
                // Create a new topic
                $topic = Topic::create(['name' => $name]);

                // Attach the classes, subjects, and chapters to the new topic
                $topic->classes()->attach($validatedData['class_id']);
                $topic->subjects()->attach($validatedData['subject_id']);
                $topic->chapters()->attach($validatedData['chapter_id']);
            }
        }

        return redirect()->route('topic.index')->with('success', 'Topics created successfully or Existing topic linked to the selected classes, subjects and chapters!');
    }



    public function edit(Topic $topic)
    {
        $topic = Topic::with('classes', 'subjects', 'chapters', 'classroom.boards', 'classroom.boards.countries')->find($topic->id);
        $pageHead = 'Edit Topic';
        $pageTitle = 'Edit Topic';
        $activeMenu = 'Topic';
        
        // Fetch all classes and subjects
        $classes = Classroom::with('subjects')->get();
        $subjects = Subject::all(); // Assuming you want all subjects, adjust as needed
        $chapters = Chapter::all(); // Assuming you want all chapters, adjust as needed
    
        return view('topics.edit', compact('activeMenu', 'pageHead', 'pageTitle', 'topic', 'classes', 'subjects', 'chapters'));
    }

    public function update(Request $request, Topic $topic)
    {

        $validatedData = $request->validate([
            'class_id' => 'required|array',
            'subject_id' => 'required|array',
            'chapter_id' => 'required|array',
            'name' => 'required|string|max:255',
        ]);

         // Update the topic name
        $topic->name = $request->name;
        $topic->save();

        // Update the topic with the selected classes, subjects, and chapters
        // Sync relationships with the selected IDs
        $topic->classes()->sync($request->class_id);
        $topic->subjects()->sync($request->subject_id);
        $topic->chapters()->sync($request->chapter_id);

        // Redirect back with a success message
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

