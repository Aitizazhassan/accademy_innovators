<?php

namespace App\Http\Controllers;

use App\Http\Requests\SubjectRequest;
use App\Models\Classroom;
use App\Models\Subject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;

class SubjectController extends Controller
{

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $currentUserId = Auth::id();
            $subject = Subject::with('classrooms', 'classrooms.boards', 'classrooms.boards.countries');

            return Datatables::of($subject)
                ->addIndexColumn()
                ->addColumn('dateAdded', function ($row) {
                    $dateAdded = \Carbon\Carbon::parse($row->created_at);
                    return '<span class="">' . date("d-m-Y", strtotime($dateAdded)) . '</span>';
                })
                ->addColumn('subject_countries', function ($row) {
                    $countries = '';
                    $addedCountries = []; 
                    if ($row->classrooms) {
                        foreach ($row->classrooms as $classroom) {
                            if ($classroom->boards) {
                                foreach ($classroom->boards as $board) {
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
                    }
                    return $countries;
                })
                ->addColumn('subject_boards', function ($row) {
                    $boards = '';
                    $addedBoards = []; 
                    if ($row->classrooms) {
                        foreach ($row->classrooms as $classroom) {
                            if ($classroom->boards) {
                                foreach ($classroom->boards as $board) {
                                    if (!in_array($board->name, $addedBoards)) {
                                        $boards .= '<span class="badge bg-primary">' . $board->name . '</span>&nbsp;';
                                        $addedBoards[] = $board->name;
                                    }
                                }
                            }
                        }
                    }
                    return $boards;
                })
                ->addColumn('subject_classrooms', function ($row) {
                    $classrooms = '';
                    if($row->classrooms){
                        foreach($row->classrooms as $classroom)
                        {
                            $classrooms .= '<span class="badge bg-primary">'.$classroom->name.'</span>&nbsp;';
                        }
                    }
                    return $classrooms;
                })

                ->addColumn('actions', function ($row) {

                    $settingsButton = '<a href="' . route('subject.edit', $row->id) . '" class="btn btn-sm btn-alt-secondary" data-bs-toggle="tooltip" title="Edit">
                                       <i class="fa fa-pencil-alt"></i>
                                   </a>';

                    $deleteButton = '<a href="#" class="btn btn-sm btn-alt-secondary delete-user" data-bs-toggle="tooltip" data-id="' . $row->id . '" title="Delete">
                                            <i class="fa fa-times"></i>
                                        </a>';

                    $settingsButton = Gate::check('user.edit') ? $settingsButton : '';
                    $deleteButton = Gate::check('user.delete') ? $deleteButton : '';

                    return '<div class="btn-group">' . $settingsButton . $deleteButton . '</div>';
                })
                ->rawColumns(['dateAdded', 'status', 'subject_boards', 'subject_countries', 'subject_classrooms', 'actions'])
                ->make(true);
        }
        $pageHead = 'Subject';
        $pageTitle = 'Subject';
        $activeMenu = 'subject';
        return view('subject.index', compact('activeMenu', 'pageHead', 'pageTitle'));
    }


    public function create()
    {

        $pageHead = 'Create Subject';
        $pageTitle = 'Create Subject';
        $activeMenu = 'create_subject';

        $classrooms = Classroom::get();

        return view('subject.create', compact('activeMenu', 'pageHead', 'pageTitle', 'classrooms'));
    }


    public function store(SubjectRequest $request)
    {
        $validatedData = $request->validate([
            'name.*' => 'required|string',
            'classroom_id' => 'required|array',
        ]);

        foreach ($validatedData['name'] as $name) {
            // Check if a topic with the same name already exists
            $existingTopic = Subject::where('name', $name)->first();
            if ($existingTopic) {
                // If a topic with the same name already exists, return with error message
                return redirect()->back()->with('error', "A Subject with the name '{$name}' already exists.");
            }

            // Create a new session instance
            $subject = new Subject();
            $subject->name = $name;
            $subject->save();
            // Attach campuses to the session
            $subject->classrooms()->attach($validatedData['classroom_id']);
        }

        return redirect()->route('subject.index')->with('success', 'Subject created successfully.');
    }




    public function edit(Subject $subject)
    {

        $pageHead = 'Edit Subject';
        $pageTitle = 'Edit Subject';
        $activeMenu = 'subject';

        $classrooms = Classroom::get();

        return view('subject.edit', compact('activeMenu', 'pageHead', 'pageTitle', 'subject', 'classrooms'));
    }

    public function update(SubjectRequest $request, Subject $subject)
    {

        $validatedData = $request->validate([
            'name' => 'required|string',
            'classroom_id' => 'required|array',
        ]);
        // Validate the incoming request data
        $validatedData = $request->validated();

        // Update session name
        $subject->name = $validatedData['name'];
        $subject->save();

        // Sync campuses for the session
        $subject->classrooms()->sync($validatedData['classroom_id']);

        return redirect()->route('subject.index')->with('success', 'Subject updated successfully.');
    }

    public function show(Subject $subject)
    {

        abort(404);
    }


    public function destroy(Request $request, $id)
    {

        try {
            // Find the board by ID
            $subject = Subject::findOrFail($id);

            // Delete the board
            $subject->delete();

            // Return a JSON response indicating success
            return response()->json(['message' => 'Subject deleted successfully']);
        } catch (ModelNotFoundException $ex) {
            // If the board doesn't exist, return a 404 Not Found response
            return response()->json(['error' => 'Subject not found'], 404);
        } catch (Exception $ex) {
            // Return a 500 Internal Server Error response if an error occurs
            return response()->json(['error' => 'Internal Server Error'], 500);
        }
    }
}
