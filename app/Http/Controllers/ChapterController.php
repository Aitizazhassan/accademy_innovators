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
use Illuminate\Support\Facades\Session;
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
            $chapters = Chapter::with('subjects', 'classes', 'classes.boards', 'classes.boards.countries')
                ->orderBy('id', 'desc');
        
            return Datatables::of($chapters)
                ->addIndexColumn()
                ->addColumn('dateAdded', function ($row) {
                    return '<span class="">' . $row->created_at->format('d-m-Y') . '</span>';
                })
                ->addColumn('chapter_countries', function ($row) {
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
                ->addColumn('chapter_boards', function ($row) {
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
                ->addColumn('chapter_classes', function ($row) {
                    $classes = '';
                    foreach ($row->classes as $class) {
                        $classes .= '<span class="badge bg-info">' . $class->name . '</span>&nbsp;';
                    }
                    return $classes;
                })
                ->addColumn('chapter_subjects', function ($row) {
                    $subjects = '';
                    foreach ($row->subjects as $subject) {
                        $subjects .= '<span class="badge bg-warning">' . $subject->name . '</span>&nbsp;';
                    }
                    return $subjects;
                })
                ->addColumn('actions', function ($row) {
                    $settingsButton = '<a href="' . route('chapter.edit', $row->id) . '" class="btn btn-sm btn-alt-secondary" data-bs-toggle="tooltip" title="Edit">
                                           <i class="fa fa-pencil-alt"></i>
                                       </a>';
        
                    $deleteButton = '<a href="#" class="btn btn-sm btn-alt-secondary delete-chapter" data-bs-toggle="tooltip" data-id="' . $row->id . '" title="Delete">
                                        <i class="fa fa-times"></i>
                                     </a>';
        
                    return '<div class="btn-group">' . $settingsButton . $deleteButton . '</div>';
                })
                ->rawColumns(['dateAdded', 'chapter_countries', 'chapter_boards', 'chapter_classes', 'chapter_subjects', 'actions'])
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
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'class_id' => 'required|array',
            'subject_id' => 'required|array',
            'name.*' => 'required|string|max:255',
        ]);
        // Create or retrieve the chapter
        foreach ($validatedData['name'] as $name) {
            // Check if a chapter with the same name exists
            $existingChapter = Chapter::where('name', $name)->first();
        
            if ($existingChapter) {
                // If chapter exists, attach the class and subject without detaching existing ones
                $existingChapter->classes()->syncWithoutDetaching($validatedData['class_id']);
                $existingChapter->subjects()->syncWithoutDetaching($validatedData['subject_id']);
        
                // Flash success message for linking
                // Session::flash('success', "The chapter '{$name}' already exists and has been linked to the selected classes and subjects.");
            } else {
                // Create a new chapter instance
                $chapter = new Chapter();
                $chapter->name = $name;
                $chapter->save();
        
                // Attach the new class and subject to the newly created chapter
                $chapter->classes()->attach($validatedData['class_id']);
                $chapter->subjects()->attach($validatedData['subject_id']);
        
                // Flash success message for creation
                // Session::flash('success', "Chapter '{$name}' created successfully and linked to the selected classes and subjects.");
            }
        }

        return redirect()->route('chapter.index')->with('success', 'Chapters created successfully or Existing Chapter linked to the selected classes and subjects!');;
    }



    public function edit(Chapter $chapter)
    {
        // Load the chapter with its classes and subjects
        $chapter = Chapter::with('classes', 'subjects')->find($chapter->id);
        
        // Set page variables
        $pageHead = 'Edit Chapter';
        $pageTitle = 'Edit Chapter';
        $activeMenu = 'chapter';
        
        // Get all available classes and subjects for selection
        $classes = Classroom::all();
        $subjects = Subject::all();
        
        return view('chapter.edit', compact('activeMenu', 'pageHead', 'pageTitle', 'chapter', 'subjects', 'classes'));
    }

    public function update(Request $request, Chapter $chapter)
    {
        $validatedData = $request->validate([
            'class_id' => 'required|array',
            'subject_id' => 'required|array',
            'name' => 'required|string|max:255',
        ]);
         // Update the chapter name
        $chapter->name = $validatedData['name'];
        $chapter->save();

        // Sync the classes and subjects
        $chapter->classes()->sync($validatedData['class_id']); // Syncs and removes the old relationships
        $chapter->subjects()->sync($validatedData['subject_id']); // Syncs and removes the old relationships

        // Flash a success message
        Session::flash('success', 'Chapter updated successfully.');

        // Redirect to a desired location
        return redirect()->route('chapter.index'); // Adjust this as necessary
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
