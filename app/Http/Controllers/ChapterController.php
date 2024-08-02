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
            $chapters = Chapter::with('subject', 'classroom', 'classroom.boards', 'classroom.boards.countries')->orderBy('id', 'desc');
            return Datatables::of($chapters)
                ->addIndexColumn()
                ->addColumn('dateAdded', function ($row) {
                    $dateAdded = \Carbon\Carbon::parse($row->created_at);
                    return '<span class="">' . date("d-m-Y", strtotime($dateAdded)) . '</span>';
                    // '<br><span class="text-muted">' . date("g:i A", strtotime($dateAdded)) . '</span>';
                })
                ->addColumn('chapter_countries', function ($row) {
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
                ->addColumn('chapter_boards', function ($row) {
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
                // ->addColumn('chapter_classrooms', function ($row) {
                //     $classrooms = '';
                //     $addedClassrooms = []; 
                    
                //     if ($row->subjects) {
                //         foreach ($row->subjects as $subject) {
                //             if ($subject->classrooms) {
                //                 foreach ($subject->classrooms as $classroom) {
                //                     if (!in_array($classroom->name, $addedClassrooms)) {
                //                         $classrooms .= '<span class="badge bg-primary">' . $classroom->name . '</span>&nbsp;';
                //                         $addedClassrooms[] = $classroom->name;
                //                     }
                //                 }
                //             }
                //         }
                //     }
                //     return $classrooms;
                // })
                ->addColumn('chapter_classroom', function ($row) {
                    $classroom = '';
                    if ($row->classroom) {
                        $classroom = $row->classroom->name;
                    }
                    return $classroom;
                })
                // ->addColumn('chapter_subject', function ($row) {
                //     $subjects = '';
                //     if ($row->subjects) {
                //         foreach ($row->subjects as $subject) {
                //             $subjects .= '<span class="badge bg-primary">' . $subject->name . '</span>&nbsp;';
                //         }
                //     }
                //     return $subjects;
                // })

                
                ->addColumn('chapter_subject', function ($row) {
                    $subject = '';
                    if ($row->subject) {
                            $subject = $row->subject->name;
                    }
                    return $subject;
                })

                ->addColumn('actions', function ($row) {

                    $settingsButton = '<a href="' . route('chapter.edit', $row->id) . '" class="btn btn-sm btn-alt-secondary" data-bs-toggle="tooltip" title="Edit">
                                       <i class="fa fa-pencil-alt"></i>
                                   </a>';

                    $deleteButton = '<a href="#" class="btn btn-sm btn-alt-secondary delete-chapter" data-bs-toggle="tooltip" data-id="' . $row->id . '" title="Delete">
                                            <i class="fa fa-times"></i>
                                        </a>';

                    $settingsButton = Gate::check('user.edit') ? $settingsButton : '';
                    $deleteButton = Gate::check('user.delete') ? $deleteButton : '';

                    return '<div class="btn-group">' . $settingsButton . $deleteButton . '</div>';
                })
                ->rawColumns(['dateAdded', 'status', 'chapter_countries', 'chapter_boards', 'chapter_classroom', 'chapter_subject', 'actions'])
                ->make(true);
        }
        $pageHead = 'Chapter';
        $pageTitle = 'Chapter';
        $activeMenu = 'chapter';
        return view('chapter.index', compact('activeMenu', 'pageHead', 'pageTitle'));
    }

    public function create()
    {

        $pageHead = 'Create Chapter';
        $pageTitle = 'Create Chapter';
        $activeMenu = 'create_chapter';

        $classes = Classroom::get();

        return view('chapter.create', compact('activeMenu', 'pageHead', 'pageTitle', 'classes'));
    }

    // public function store(ChapterRequest $request)
    // {

    //     $validatedData = $request->validate([
    //         'name' => 'required|string',
    //         'subject_id' => 'required|array',
    //     ]);


    //     // Validate the incoming request data
    //     $validatedData = $request->validated();

    //     // Check if a session with the same name already exists
    //     $existingSession = Chapter::where('name', $validatedData['name'])->first();

    //     if ($existingSession) {
    //         // If a session with the same name already exists, return with error message
    //         return redirect()->back()->with('error', 'A chapter with the same name already exists.');
    //     }

    //     // Create a new session instance
    //     $chapter = new Chapter();
    //     $chapter->name = $validatedData['name'];
    //     $chapter->save();

    //     // Attach campuses to the session
    //     $chapter->subjects()->attach($validatedData['subject_id']);

    //     return redirect()->route('chapter.index')->with('success', 'Chapter created successfully.');

    // }
    public function store(ChapterRequest $request)
    {
        $validatedData = $request->validate([
            'class_id' => 'required',
            'subject_id' => 'required',
            'name.*' => 'required|string',
        ]);

        foreach ($validatedData['name'] as $name) {
            $existingTopic = Chapter::where('name', $name)->first();
            if ($existingTopic) {
                return redirect()->back()->with('error', "A Chapter with the name '{$name}' already exists.");
            }

            // Create a new topic instance
            $chapter = new Chapter();
            $chapter->classroom_id =  $request->class_id;
            $chapter->subject_id =  $request->subject_id;
            $chapter->name =  $name;
            $chapter->save();

            // Attach campuses to the session
            // $chapter->subjects()->attach($validatedData['subject_id']);
        }

        return redirect()->route('chapter.index')->with('success', 'Chapter created successfully.');
    }



    public function edit(Chapter $chapter)
    {
        $chapter = Chapter::with('classroom', 'classroom.subjects')->find($chapter->id);
        $pageHead = 'Edit Chapter';
        $pageTitle = 'Edit Chapter';
        $activeMenu = 'chapter';
        
        $classes = Classroom::get();
        $subjects = $chapter->classroom->subjects;

        return view('chapter.edit', compact('activeMenu', 'pageHead', 'pageTitle', 'chapter', 'subjects', 'classes'));
    }

    public function update(ChapterRequest $request, Chapter $chapter)
    {

        $validatedData = $request->validate([
            'class_id' => 'required',
            'subject_id' => 'required',
            'name.*' => 'required|string',
        ]);
        // Validate the incoming request data
        $validatedData = $request->validated();

        // Update session name
        $chapter->name = $validatedData['name'];
        $chapter->classroom_id =  $request->class_id;
        $chapter->subject_id =  $request->subject_id;
        $chapter->save();

        // Sync campuses for the session
        // $chapter->subjects()->sync($validatedData['subject_id']);

        return redirect()->route('chapter.index')->with('success', 'Chapter updated successfully.');
    }

    public function show(Chapter $chapter)
    {

        abort(404);
    }



    public function destroy(Request $request, $id)
    {

        try {
            // Find the chapter by ID
            $chapter = Chapter::findOrFail($id);

            // Delete the chapter
            $chapter->delete();

            // Return a JSON response indicating success
            return response()->json(['message' => 'Chapter deleted successfully.']);
        } catch (\Exception $ex) {
            // Return a 500 Internal Server Error response if an error occurs
            return response()->json(['error' => 'Internal Server Error'. $ex->getMessage()], 500);
        }
    }
}
