<?php

namespace App\Http\Controllers;

use App\Http\Requests\McqsRequest;
use App\Models\MCQs;
use App\Models\Board;
use App\Models\Classroom;
use App\Models\Chapter;
use App\Models\Subject;
use App\Models\Topic;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;

class MCQsController extends Controller
{

    public $classSubjects;

    // public function __construct()
    // {

    //     $this->classSubjects = Subject::classRoomSubjects()->get();

    // }

    public function index(Request $request)
{
    if ($request->ajax()) {
        $data = MCQs::with(['board', 'class', 'subject', 'chapter', 'topic'])->get();

        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('board_name', function($row){
                return $row->board->name;
            })
            ->addColumn('class_name', function($row){
                return $row->class->name;
            })
            ->addColumn('subject_name', function($row){
                return $row->subject->name;
            })
            ->addColumn('chapter_name', function($row){
                return $row->chapter->name;
            })
            ->addColumn('topic_name', function($row){
                return $row->topic->name;
            })
            ->addColumn('statement', function($row){
                return $row->statement;
            })
            ->addColumn('optionA', function($row){
                return $row->optionA;
            })
            ->addColumn('optionB', function($row){
                return $row->optionB;
            })
            ->addColumn('optionC', function($row){
                return $row->optionC;
            })
            ->addColumn('optionD', function($row){
                return $row->optionD;
            })
            ->addColumn('solution_link_english', function($row){
                return $row->solution_link_english;
            })
            ->addColumn('solution_link_urdu', function($row){
                return $row->solution_link_urdu;
            })
            ->addColumn('dateAdded', function($row){
                return $row->created_at->format('Y-m-d');
            })
            ->addColumn('actions', function($row){
                return view('partials.action-buttons', compact('row'))->render();
            })
            ->rawColumns(['actions'])
            ->make(true);
    }

    $pageHead = 'MCQs';
    $pageTitle = 'MCQs';
    $activeMenu = 'MCQs';
    return view('mcqs-question.index', compact('activeMenu', 'pageHead', 'pageTitle'));
}

    public function create()
    {
        $pageHead = 'Create MCQs';
        $pageTitle = 'Create MCQs';
        $activeMenu = 'create_MCQs';

        $board = Board::all();
        return view('mcqs-question.create', compact('activeMenu', 'pageHead', 'pageTitle', 'board'));
    }

    public function getClass($board_id)
    {
        $classes = Classroom::whereHas('boards', function ($query) use ($board_id) {
            $query->where('board_id', $board_id);
        })->get();

        return response()->json($classes);
    }

    public function getSubjects($classroom_id)
    {
        // Retrieve the subjects associated with the given classroom_id
        $subjects = Subject::whereHas('classrooms', function ($query) use ($classroom_id) {
            $query->where('classroom_id', $classroom_id);
        })->get();

        return response()->json($subjects);
    }
    public function getChapters($subject_id)
    {
        // Assuming your pivot table is named chapter_subject and has 'chapter_id' and 'subject_id' columns
        $chapters = Chapter::whereHas('subjects', function ($query) use ($subject_id) {
            $query->where('subject_id', $subject_id);
        })->get();

        return response()->json($chapters);
    }

    public function getTopics($chapter_id)
    {
        // Assuming you have defined a many-to-many relationship between Topic and Chapter
        // and the intermediate table is named topic_chapter
        $topics = Chapter::findOrFail($chapter_id)->topics;

        return response()->json($topics);
    }

    public function store(Request $request)
    {

        $request->validate([
            'board_id' => 'required',
            'subject_id' => 'required',
            'chapter_id' => 'required',
            'topic_id' => 'required',
            'class_id' => 'required',
            'statement' => 'required',
            'optionA' => 'required',
            'optionB' => 'required',
            'optionC' => 'required',
            'optionD' => 'required',
            'solution_link_english' => 'nullable|string',
            'solution_link_urdu' => 'nullable|string',
        ]);

        $mcq = new MCQs();
        $mcq->board_id = $request->board_id;
        $mcq->subject_id = $request->subject_id;
        $mcq->chapter_id = $request->chapter_id;
        $mcq->topic_id = $request->topic_id;
        $mcq->class_id = $request->class_id;
        $mcq->statement = $request->statement;
        $mcq->optionA = $request->optionA;
        $mcq->optionB = $request->optionB;
        $mcq->optionC = $request->optionC;
        $mcq->optionD = $request->optionD;
        $mcq->solution_link_english = $request->solution_link_english;
        $mcq->solution_link_urdu = $request->solution_link_urdu;
        $mcq->save();

        return redirect()->route('mcqs.index')->with('success', 'MCQ created successfully.');
    }



    public function edit(MCQs $mcq)
    {
        $pageHead = 'Edit MCQs';
        $pageTitle = 'Edit MCQs';
        $activeMenu = 'MCQs';

        // Fetch all boards, classes, subjects, chapters, and topics to populate the dropdowns
        $boards = Board::all();
        $classes = Classroom::all(); // Assuming ClassName is the name of your class model
        $subjects = Subject::all();
        $chapters = Chapter::all();
        $topics = Topic::all();

        return view('mcqs-question.edit', compact('activeMenu', 'pageHead', 'pageTitle', 'mcq', 'boards', 'classes', 'subjects', 'chapters', 'topics'));
    }


    public function update(Request $request, MCQs $mcq)
    {
        $validated = $request->validate([
            'board_id' => 'required',
            'class_id' => 'required',
            'subject_id' => 'required',
            'chapter_id' => 'required',
            'topic_id' => 'required',
            'statement' => 'required',
            'optionA' => 'required',
            'optionB' => 'required',
            'optionC' => 'required',
            'optionD' => 'required',
            'solution_link_english' => 'nullable|string',
            'solution_link_urdu' => 'nullable|string',
        ]);

        $mcq->update($validated);

        return redirect()->route('mcqs.index')->with('success', 'MCQ updated successfully.');
    }

    public function show(Topic $chapter)
    {

        abort(404);
    }

    public function destroy(Request $request, $id)
    {
        try {
            // Find the board by ID
            $mcqs = MCQs::findOrFail($id);

            // Delete the board
            $mcqs->delete();

            // Return a JSON response indicating success
            return response()->json(['message' => 'MCQs deleted successfully.']);
        } catch (ModelNotFoundException $ex) {
            // If the board doesn't exist, return a 404 Not Found response
            return response()->json(['error' => 'MCQs not found'], 404);
        } catch (Exception $ex) {
            // Return a 500 Internal Server Error response if an error occurs
            return response()->json(['error' => 'Internal Server Error'], 500);
        }
    }
}
