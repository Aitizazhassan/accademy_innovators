<?php

namespace App\Http\Controllers;

use App\Models\MCQs;
use App\Models\Country;
use App\Models\Board;
use App\Models\Topic;
use App\Models\Chapter;
use App\Models\Subject;
use App\Models\Classroom;
use Illuminate\Http\Request;
use App\Http\Requests\McqsRequest;
use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
use Endroid\QrCode\Encoding\Encoding;
use PhpOffice\PhpPresentation\IOFactory;
use Yajra\DataTables\Facades\DataTables;
use PhpOffice\PhpPresentation\Style\Font;
use PhpOffice\PhpPresentation\Style\Color;
use PhpOffice\PhpPresentation\PhpPresentation;
use PhpOffice\PhpPresentation\Style\Alignment;
use PhpOffice\PhpPresentation\Slide\SlideLayout;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Endroid\QrCode\ErrorCorrectionLevel\ErrorCorrectionLevelHigh;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;
use Barryvdh\DomPDF\Facade\PDF as PDF;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

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
            $query = MCQs::with(['countries', 'boards', 'classes', 'subjects', 'chapters', 'topics']);
    
            // Filter by solution links
            $solutionFilter = $request->input('solutionFilter');
            if ($solutionFilter) {
                switch ($solutionFilter) {
                    case 'english':
                        $query->whereNotNull('solution_link_english');
                        break;
                    case 'urdu':
                        $query->whereNotNull('solution_link_urdu');
                        break;
                    case 'no_english':
                            $query->whereNull('solution_link_english');
                            break;
                    case 'no_urdu':
                            $query->whereNull('solution_link_urdu');
                            break;
                    case 'both':
                        $query->whereNotNull('solution_link_english')
                              ->whereNotNull('solution_link_urdu');
                        break;
                    case 'none':
                        $query->whereNull('solution_link_english')
                              ->whereNull('solution_link_urdu');
                        break;
                }
            }
    
            $data = $query->get();

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('country_name', function ($row) {
                    return $row->countries->pluck('name')->map(function($name) {
                        return '<span class="badge bg-primary">' . $name . '</span>';
                    })->implode(' ');
                })
                ->addColumn('board_name', function ($row) {
                    return $row->boards->pluck('name')->map(function($name) {
                        return '<span class="badge bg-primary">' . $name . '</span>';
                    })->implode(' ');
                })
                ->addColumn('class_name', function ($row) {
                    return $row->classes->pluck('name')->map(function($name) {
                        return '<span class="badge bg-primary">' . $name . '</span>';
                    })->implode(' ');
                })
                ->addColumn('subject_name', function ($row) {
                    return $row->subjects->pluck('name')->map(function($name) {
                        return '<span class="badge bg-primary">' . $name . '</span>';
                    })->implode(' ');
                })
                ->addColumn('chapter_name', function ($row) {
                    return $row->chapters->pluck('name')->map(function($name) {
                        return '<span class="badge bg-primary">' . $name . '</span>';
                    })->implode(' ');
                })
                ->addColumn('topic_name', function ($row) {
                    return $row->topics->pluck('name')->map(function($name) {
                        return '<span class="badge bg-primary">' . $name . '</span>';
                    })->implode(' ');
                })
                ->addColumn('statement', function ($row) {
                    return $row->statement;
                })
                ->addColumn('optionA', function ($row) {
                    return $row->optionA;
                })
                ->addColumn('optionB', function ($row) {
                    return $row->optionB;
                })
                ->addColumn('optionC', function ($row) {
                    return $row->optionC;
                })
                ->addColumn('optionD', function ($row) {
                    return $row->optionD;
                })
                ->addColumn('solution_link_english', function ($row) {
                    if (!empty($row->solution_link_english)) {
                        return $this->generateQrCodeImage($row->solution_link_english);
                    }
                    return '';
                })
                ->addColumn('solution_link_urdu', function ($row) {
                    if (!empty($row->solution_link_urdu)) {
                        return $this->generateQrCodeImage($row->solution_link_urdu);
                    }
                    return '';
                })
                ->addColumn('dateAdded', function ($row) {
                    return $row->created_at->format('Y-m-d');
                })
                ->addColumn('actions', function ($row) {
                    return view('partials.action-buttons', compact('row'))->render();
                })
                ->rawColumns(['country_name', 'board_name', 'class_name', 'subject_name', 'chapter_name', 'topic_name', 'solution_link_english', 'solution_link_urdu', 'actions'])
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

        $countries = Country::all();
        return view('mcqs-question.create', compact('activeMenu', 'pageHead', 'pageTitle', 'countries'));
    }

    // public function getboards($country_id)
    // {
    //     $boards = Board::whereHas('countries', function ($query) use ($country_id) {
    //         $query->where('country_id', $country_id);
    //     })->get();

    //     return response()->json($boards);
    // }
    public function getBoards($country_id)
    {
        $country_id = explode(',', $country_id);

        $boards = Board::whereHas('countries', function ($query) use ($country_id) {
            if (is_array($country_id)) {
                $query->whereIn('country_id', $country_id);
            } else {
                $query->where('country_id', $country_id);
            }
        })->get();

        return response()->json($boards);
    }
    // public function getClass($board_id)
    // {
    //     $classes = Classroom::whereHas('boards', function ($query) use ($board_id) {
    //         $query->where('board_id', $board_id);
    //     })->get();

    //     return response()->json($classes);
    // }

    public function getClass($board_id)
    {
        // Handle both single and multiple board IDs
        $board_id = explode(',', $board_id);

        $classes = Classroom::whereHas('boards', function ($query) use ($board_id) {
            if (is_array($board_id)) {
                $query->whereIn('board_id', $board_id);
            } else {
                $query->where('board_id', $board_id);
            }
        })->get();

        return response()->json($classes);
    }

    public function getSubjects($classroom_id)
    {
        // Retrieve the subjects associated with the given classroom_id
        $classroom_id = explode(',', $classroom_id);
        $subjects = Subject::whereHas('classrooms', function ($query) use ($classroom_id) {
            if(is_array($classroom_id)){
                $query->whereIn('classroom_id', $classroom_id);
            } else {
                $query->where('classroom_id', $classroom_id);
            }
        })->get();

        return response()->json($subjects);
    }
    public function getChapters($subject_id)
    {
        $subjectIds = explode(',', $subject_id);
        $chapters = collect(); // Initialize a collection to store unique chapters

        if (is_array($subjectIds) && count($subjectIds) > 0) {
            $subjects = Subject::whereIn('id', $subjectIds)->with('chapters')->get();
            
            foreach ($subjects as $subject) {
                // Merge chapters into the collection
                $chapters = $chapters->merge($subject->chapters);
            }
        } else {
            $subject = Subject::with('chapters')->find($subject_id);
            if ($subject) {
                $chapters = $subject->chapters;
            }
        }

        // Filter out duplicates based on the chapter ID
        $uniqueChapters = $chapters->unique('id')->values()->all();

        return response()->json($uniqueChapters);
    }

    // public function getTopics($chapter_id)
    // {
    //     // Assuming you have defined a many-to-many relationship between Topic and Chapter
    //     // and the intermediate table is named topic_chapter
    //     $topics = Chapter::findOrFail($chapter_id)->topics;

    //     return response()->json($topics);
    // }

    public function getTopics($chapter_id)
    {
        $chapterIds = explode(',', $chapter_id);
        $topics = collect(); // Initialize a collection to store topics

        if (is_array($chapterIds) && count($chapterIds) > 0) {
            $chapters = Chapter::whereIn('id', $chapterIds)->with('topics')->get();

            foreach ($chapters as $chapter) {
                // Merge topics into the collection, avoiding duplicates
                $topics = $topics->merge($chapter->topics);
            }
        } else {
            $chapter = Chapter::with('topics')->find($chapter_id);
            if ($chapter) {
                $topics = $chapter->topics;
            }
        }

        // Filter out duplicates based on the topic ID
        $uniqueTopics = $topics->unique('id')->values()->all();

        return response()->json($uniqueTopics);
    }

    public function store(Request $request)
    {
        $request->validate([
            'country_id' => 'required',
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
        ], [
            'country_id.required' => 'The country selection is required.',
            'board_id.required' => 'Please select a board.',
            'subject_id.required' => 'Please select a subject.',
            'chapter_id.required' => 'You must select a chapter.',
            'topic_id.required' => 'Selecting a topic is required.',
            'class_id.required' => 'The class field is required.',
            'statement.required' => 'The question statement is required.',
            'optionA.required' => 'Option A is required.',
            'optionB.required' => 'Option B is required.',
            'optionC.required' => 'Option C is required.',
            'optionD.required' => 'Option D is required.',
        ]);

        $mcq = new MCQs();
        $mcq->statement = $request->statement;
        $mcq->optionA = $request->optionA;
        $mcq->optionB = $request->optionB;
        $mcq->optionC = $request->optionC;
        $mcq->optionD = $request->optionD;
        $mcq->solution_link_english = $request->solution_link_english;
        $mcq->solution_link_urdu = $request->solution_link_urdu;
        $mcq->save();

         // Sync relations
        $mcq->countries()->sync($request->input('country_id'));
        $mcq->boards()->sync($request->input('board_id'));
        $mcq->classes()->sync($request->input('class_id'));
        $mcq->subjects()->sync($request->input('subject_id'));
        $mcq->chapters()->sync($request->input('chapter_id'));
        $mcq->topics()->sync($request->input('topic_id'));

        return redirect()->route('mcqs.index')->with('success', 'MCQ created successfully.');
    }



    public function edit(MCQs $mcq)
    {
        $mcq = MCQs::with('countries', 'countries.board', 'boards', 'boards.classrooms', 'classes', 'classes.subjects', 'subjects', 'subjects.chapters', 'chapters', 'chapters.topics', 'topics')->findOrFail($mcq->id);
        $pageHead = 'Edit MCQs';
        $pageTitle = 'Edit MCQs';
    
        // Fetch related data for the dropdowns
        $countries = Country::all();
        $boards = $mcq->countries->flatMap(function ($country) {
            return $country->board;
        })->unique('id');
        $classes = $mcq->boards->flatMap(function ($board) {
            return $board->classrooms;
        })->unique('id');
        
        $subjects = $mcq->classes->flatMap(function ($class) {
            return $class->subjects;
        })->unique('id');

        $chapters = $mcq->subjects->flatMap(function ($subject) {
            return $subject->chapters;
        })->unique('id');

        $topics = $mcq->chapters->flatMap(function ($chapter) {
            return $chapter->topics;
        })->unique('id');

        return view('mcqs-question.edit', compact(
            'mcq', 'countries', 'boards', 'classes', 'subjects', 'chapters', 'topics', 'pageHead', 'pageTitle'
        ));

    }


    public function update(Request $request, MCQs $mcq)
    {
        $request->validate([
            'country_id' => 'required',
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
        ], [
            'country_id.required' => 'The country selection is required.',
            'board_id.required' => 'Please select a board.',
            'subject_id.required' => 'Please select a subject.',
            'chapter_id.required' => 'You must select a chapter.',
            'topic_id.required' => 'Selecting a topic is required.',
            'class_id.required' => 'The class field is required.',
            'statement.required' => 'The question statement is required.',
            'optionA.required' => 'Option A is required.',
            'optionB.required' => 'Option B is required.',
            'optionC.required' => 'Option C is required.',
            'optionD.required' => 'Option D is required.',
        ]);
        // $mcq->update($validated);

        $mcq->statement = $request->input('statement');
        $mcq->optionA = $request->input('optionA');
        $mcq->optionB = $request->input('optionB');
        $mcq->optionC = $request->input('optionC');
        $mcq->optionD = $request->input('optionD');
        $mcq->solution_link_english = $request->input('solution_link_english');
        $mcq->solution_link_urdu = $request->input('solution_link_urdu');
        $mcq->save();

        // Sync relations
        $mcq->countries()->sync($request->input('country_id'));
        $mcq->boards()->sync($request->input('board_id'));
        $mcq->classes()->sync($request->input('class_id'));
        $mcq->subjects()->sync($request->input('subject_id'));
        $mcq->chapters()->sync($request->input('chapter_id'));
        $mcq->topics()->sync($request->input('topic_id'));

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
        } catch (\Exception $ex) {
            // Return a 500 Internal Server Error response if an error occurs
            return response()->json(['error' => 'Internal Server Error'], 500);
        }
    }

    // public function downloadSingleMcqAsPptx($id)
    // {
    //     $mcq = Mcqs::findOrFail($id);
    
    //     // Create a new PHPPresentation object
    //     $objPHPPresentation = new PhpPresentation();
    
    //     // Remove the default slide
    //     $objPHPPresentation->removeSlideByIndex(0);
    
    //     // Create a new slide
    //     $currentSlide = $objPHPPresentation->createSlide();
    
    //     // Add question text
    //     $questionShape = $currentSlide->createRichTextShape()
    //         ->setHeight(100)
    //         ->setWidth(600)
    //         ->setOffsetX(50)
    //         ->setOffsetY(50);
    //     $questionShape->getActiveParagraph()->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
    //     $textRun = $questionShape->createTextRun(str_replace('&nbsp;', ' ', $this->stripHtmlTags($mcq->statement)));
    //     $textRun->getFont()->setBold(true)
    //         ->setSize(18)
    //         ->setColor(new Color('FF000000'));
    
    //     // Add options
    //     $options = [
    //         'A' => $this->stripHtmlTags($mcq->optionA),
    //         'B' => $this->stripHtmlTags($mcq->optionB),
    //         'C' => $this->stripHtmlTags($mcq->optionC),
    //         'D' => $this->stripHtmlTags($mcq->optionD)
    //     ];
    //     $offsetY = 150;
    //     foreach ($options as $key => $value) {
    //         $optionShape = $currentSlide->createRichTextShape()
    //             ->setHeight(50)
    //             ->setWidth(600)
    //             ->setOffsetX(50)
    //             ->setOffsetY($offsetY);
    //         $textRun = $optionShape->createTextRun("{$key}) {$value}");
    //         $textRun->getFont()->setSize(16)
    //             ->setColor(new Color('FF000000'));
    //         $offsetY += 60;
    //     }
    
    //     // Generate QR codes for solution links
    //     $qrCodeOffsetY = $offsetY + 50;
    //     if (!empty($mcq->solution_link_english)) {
    //         $this->addQrCodeToSlide($currentSlide, $mcq->solution_link_english, 'English Solution', 50, $qrCodeOffsetY);
    //     }
    //     if (!empty($mcq->solution_link_urdu)) {
    //         $this->addQrCodeToSlide($currentSlide, $mcq->solution_link_urdu, 'Urdu Solution', 200, $qrCodeOffsetY); 
    //     }
    
    //     // Save the presentation to a temporary file
    //     $pptxFile = storage_path('app/public/mcq_' . $id . '.pptx');
    //     $objWriter = IOFactory::createWriter($objPHPPresentation, 'PowerPoint2007');
    //     $objWriter->save($pptxFile);
    
    //     // Return the file as a download response
    //     return response()->download($pptxFile)->deleteFileAfterSend(true);
    // }

//     public function downloadSingleMcqAsPptx($id)
// {
//     $mcq = Mcqs::findOrFail($id);

//     // Create a new PHPPresentation object
//     $objPHPPresentation = new PhpPresentation();

//     // Remove the default slide
//     $objPHPPresentation->removeSlideByIndex(0);

//     // Create a new slide
//     $currentSlide = $objPHPPresentation->createSlide();

//     // Define initial offsets
//     $offsetX = 50;
//     $offsetY = 50;

//     // Add question statement
//     $questionShape = $currentSlide->createRichTextShape()
//         ->setHeight(0) // Start with 0; the height will adjust dynamically
//         ->setWidth(600) // Set width for proper text wrapping
//         ->setOffsetX($offsetX)
//         ->setOffsetY($offsetY);
//     $questionShape->getActiveParagraph()->getAlignment()
//         ->setHorizontal(Alignment::HORIZONTAL_LEFT);
//     $textRun = $questionShape->createTextRun($this->stripHtmlTags($mcq->statement));
//     $textRun->getFont()->setBold(true)
//         ->setSize(18)
//         ->setColor(new Color('FF000000'));

//     // Dynamically calculate height of the question text
//     $questionHeight = $this->calculateTextHeight($mcq->statement, 600, 18);
//     $questionShape->setHeight($questionHeight);

//     // Adjust offset for options
//     $offsetY += $questionHeight + 20; // Add space below the question

//     // Add options
//     $options = [
//         'A' => $this->stripHtmlTags($mcq->optionA),
//         'B' => $this->stripHtmlTags($mcq->optionB),
//         'C' => $this->stripHtmlTags($mcq->optionC),
//         'D' => $this->stripHtmlTags($mcq->optionD),
//     ];
//     foreach ($options as $key => $value) {
//         $optionShape = $currentSlide->createRichTextShape()
//             ->setHeight(50)
//             ->setWidth(600) // Set width for wrapping
//             ->setOffsetX($offsetX)
//             ->setOffsetY($offsetY);
//         $optionShape->getActiveParagraph()->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
//         $textRun = $optionShape->createTextRun("{$key}) {$value}");
//         $textRun->getFont()->setSize(16)
//             ->setColor(new Color('FF000000'));

//         // Adjust offset for next option
//         $offsetY += 60; // Space between options
//     }

//     // Adjust offset for QR codes
//     $qrCodeOffsetY = $offsetY + 20; // Add space below the options
//     if (!empty($mcq->solution_link_english)) {
//         $this->addQrCodeToSlide($currentSlide, $mcq->solution_link_english, 'English Solution', 50, $qrCodeOffsetY);
//     }
//     if (!empty($mcq->solution_link_urdu)) {
//         $this->addQrCodeToSlide($currentSlide, $mcq->solution_link_urdu, 'Urdu Solution', 250, $qrCodeOffsetY);
//     }

//     // Save the presentation to a temporary file
//     $pptxFile = storage_path('app/public/mcq_' . $id . '.pptx');
//     $objWriter = IOFactory::createWriter($objPHPPresentation, 'PowerPoint2007');
//     $objWriter->save($pptxFile);

//     // Return the file as a download response
//     return response()->download($pptxFile)->deleteFileAfterSend(true);
// }

        public function downloadSingleMcqAsPptx($id)
        {
            $mcq = Mcqs::findOrFail($id);

            // Create a new PHPPresentation object
            $objPHPPresentation = new PhpPresentation();

            // Remove the default slide
            $objPHPPresentation->removeSlideByIndex(0);

            // Create a new slide
            $currentSlide = $objPHPPresentation->createSlide();

            // Define initial offsets
            $offsetX = 50;
            $offsetY = 50;

            // Add question statement with rich text parsing
            $questionShape = $currentSlide->createRichTextShape()
                ->setHeight(0)
                ->setWidth(600)
                ->setOffsetX($offsetX)
                ->setOffsetY($offsetY);
            $questionShape->getActiveParagraph()->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);

            $this->parseAndApplyRichText($questionShape, $mcq->statement);

            // Adjust offset dynamically based on the question's height
            $questionHeight = $this->calculateTextHeight($mcq->statement, 600, 18);
            $questionShape->setHeight($questionHeight);
            $offsetY += $questionHeight + 20;

            // Add options with rich text parsing
            $options = [
                    'A' => $this->stripHtmlTags($mcq->optionA),
                    'B' => $this->stripHtmlTags($mcq->optionB),
                    'C' => $this->stripHtmlTags($mcq->optionC),
                    'D' => $this->stripHtmlTags($mcq->optionD),
            ];

            foreach ($options as $key => $value) {
                $optionShape = $currentSlide->createRichTextShape()
                    ->setHeight(0)
                    ->setWidth(600)
                    ->setOffsetX($offsetX)
                    ->setOffsetY($offsetY);
                $optionShape->getActiveParagraph()->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);

                $this->parseAndApplyRichText($optionShape, "{$key}) {$value}");

                // Adjust offset dynamically based on the option's height
                $optionHeight = $this->calculateTextHeight("{$key}) {$value}", 600, 16);
                $optionShape->setHeight($optionHeight);
                $offsetY += $optionHeight + 10; // Add space between options
            }

            // Add QR codes for solutions
            $qrCodeOffsetY = $offsetY + 20; // Add space below the options
            if (!empty($mcq->solution_link_english)) {
                $this->addQrCodeToSlide($currentSlide, $mcq->solution_link_english, 'English Solution', 50, $qrCodeOffsetY);
            }
            if (!empty($mcq->solution_link_urdu)) {
                $this->addQrCodeToSlide($currentSlide, $mcq->solution_link_urdu, 'Urdu Solution', 250, $qrCodeOffsetY);
            }

            // Save the presentation to a temporary file
            $pptxFile = storage_path('app/public/mcq_' . $id . '.pptx');
            $objWriter = IOFactory::createWriter($objPHPPresentation, 'PowerPoint2007');
            $objWriter->save($pptxFile);

            // Return the file as a download response
            return response()->download($pptxFile)->deleteFileAfterSend(true);
        }

        // Helper function to parse and apply rich text
        private function parseAndApplyRichText($shape, $html)
        {
            $dom = new \DOMDocument();
            @$dom->loadHTML(mb_convert_encoding($html, 'HTML-ENTITIES', 'UTF-8'));
            $body = $dom->getElementsByTagName('body')->item(0);

            foreach ($body->childNodes as $child) {
                $this->applyFormatting($shape, $child);
            }
        }

        // Helper function to apply formatting based on HTML tags
        private function applyFormatting($shape, $node)
        {
            if ($node->nodeType === XML_TEXT_NODE) {
                $textRun = $shape->createTextRun($node->textContent);
            } elseif ($node->nodeType === XML_ELEMENT_NODE) {
                $textRun = $shape->createTextRun($node->textContent);
                $tagName = $node->nodeName;

                switch ($tagName) {
                    case 'b':
                        $textRun->getFont()->setBold(true);
                        break;
                    case 'i':
                        $textRun->getFont()->setItalic(true);
                        break;
                    case 'u':
                        $textRun->getFont()->setUnderline(true);
                        break;
                    case 'span': // Handle inline styles if present
                        $style = $node->getAttribute('style');
                        if (strpos($style, 'color:') !== false) {
                            preg_match('/color:\s*([^;]+)/', $style, $matches);
                            $textRun->getFont()->setColor(new Color($matches[1]));
                        }
                        break;
                    // Add cases for other tags if necessary
                }
            }
        }

        // Helper function to calculate text height
        private function calculateTextHeight($text, $width, $fontSize)
        {
            $lineHeight = $fontSize * 1.2; // Approximate line height
            $lines = ceil(strlen(strip_tags($text)) / ($width / ($fontSize * 0.6))); // Estimate lines based on width and font size
            return $lines * $lineHeight;
        }
    
    private function addQrCodeToSlide($slide, $link, $label, $offsetX, $offsetY)
    {
        // Generate QR code
        $qrCode = QrCode::create($link);
        $writer = new PngWriter();
        $qrCodeImage = $writer->write($qrCode);
        $qrCodeFilePath = storage_path('app/public/qrcode_' . md5($link) . '.png');
        $qrCodeImage->saveToFile($qrCodeFilePath);
    
        // Add QR code to the slide
        $qrCodeShape = $slide->createDrawingShape();
        $qrCodeShape->setPath($qrCodeFilePath)
            ->setHeight(100)
            ->setWidth(100)
            ->setOffsetX($offsetX)
            ->setOffsetY($offsetY);
    
        // Add label below QR code
        $labelShape = $slide->createRichTextShape()
            ->setHeight(30)
            ->setWidth(200)
            ->setOffsetX($offsetX - 50)
            ->setOffsetY($offsetY + 110); // Adjusted to be below the QR code
        $labelShape->getActiveParagraph()->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $textRun = $labelShape->createTextRun($label);
        $textRun->getFont()->setSize(14)
            ->setColor(new Color('FF000000'));
    }
    
    private function stripHtmlTags($content)
    {
        // return strip_tags($content);
        return html_entity_decode(strip_tags($content));
    }

    /**
     * Function to calculate text height dynamically based on content, width, and font size.
     */
    // private function calculateTextHeight($text, $width, $fontSize)
    // {
    //     // Approximate number of characters that fit in one line
    //     $charsPerLine = floor($width / ($fontSize * 0.6));
    //     $lines = ceil(strlen($text) / $charsPerLine);
    //     return $lines * $fontSize * 1.5; // 1.5 is the line-height multiplier
    // }

    // genrate Qr scan code to provide link
    private function generateQrCodeImage($link)
    {
        $qrCode = QrCode::create($link);
        $writer = new PngWriter();
        $qrCodeImage = $writer->write($qrCode);
        $qrCodeDataUri = $qrCodeImage->getDataUri();
        
        return '<img src="' . $qrCodeDataUri . '" alt="QR Code" style="width:50px;height:50px;"/>';
    }

    public function exportMCQsToPDF()
    {
        $mcqs = MCQs::all();
        $mcqs = $mcqs->map(function ($mcq) {
            $mcq->qr_code_english = $mcq->solution_link_english ? $this->generateQrCodeImage($mcq->solution_link_english) : null;
            $mcq->qr_code_urdu = $mcq->solution_link_urdu ? $this->generateQrCodeImage($mcq->solution_link_urdu) : null;
            return $mcq;
        });
        return $this->generatePDF($mcqs);
    }

    public function generatePDF($mcqs)
    {
        $pdf = PDF::loadView('mcqs-question.mcqs_book_format_pdf', compact('mcqs'));
        return $pdf->download('mcqs.pdf');
    }

    public function generatePDFUsingAjax($mcqs)
    {
        $pdf = PDF::loadView('mcqs-question.mcqs_book_format_pdf', compact('mcqs'));
        $pdfPath = public_path('downloads/mcqs.pdf');
        $pdf->save($pdfPath);

        return $pdfPath; 
    }

    public function viewBookFormatPDF(){
        $mcqs = MCQs::all();
    
        $mcqs = $mcqs->map(function ($mcq) {
            $mcq->qr_code_english = $mcq->solution_link_english ? $this->generateQrCodeImage($mcq->solution_link_english) : null;
            $mcq->qr_code_urdu = $mcq->solution_link_urdu ? $this->generateQrCodeImage($mcq->solution_link_urdu) : null;
            return $mcq;
        });
        
        return view('mcqs-question.mcqs_book_format_pdf', ['mcqs' => $mcqs]);
    }

    public function bookFormat()
    {
        $pageHead = 'Get Book Format MCQs';
        $pageTitle = 'Get Book Format MCQs';
        $activeMenu = 'get_book_format_MCQs';

        $countries = Country::all();
        return view('mcqs-question.book_format', compact('activeMenu', 'pageHead', 'pageTitle', 'countries'));
    }

    public function testFormat()
    {
        $pageHead = 'Get Test Format MCQs';
        $pageTitle = 'Get Test Format MCQs';
        $activeMenu = 'get_test_format_MCQs';

        $countries = Country::all();
        return view('mcqs-question.test-format.index', compact('activeMenu', 'pageHead', 'pageTitle', 'countries'));
        
    }
    
    // book format 
    public function getBookFormatPdf(Request $request)
    { 
        $rules = [
            'country_id' => 'required',
            'board_id' => 'required',
            'class_id' => 'required',
            'select_pathern' => 'required|in:chapter_wise,grand_test,mock_test',
            'subject_id' => 'required',
        ];
        
        if ($request->select_pathern == 'chapter_wise') {
            $rules['chapter_id'] = 'required|array';
            $rules['chapterWiseMcqs'] = 'required|string';
        }
        
        if ($request->select_pathern == 'grand_test') {
            $rules['chapter_id'] = 'required|array';
            $rules['numGrandTests'] = 'required|integer';
            $rules['questionsPerGrandTest'] = 'required|integer';
            $rules['questionsPerSubjectGrandTest'] = 'required|integer';
        }
        
        if ($request->select_pathern == 'mock_test') {
            $rules['numMockTests'] = 'required|integer';
            $rules['questionsPerMockTest'] = 'required|integer';
            $rules['questionsPerSubjectMockTest'] = 'required|integer';
        }
        
        $customMessages = [
            'country_id.required' => 'Please select a country.',
            'board_id.required' => 'Please select a board.',
            'class_id.required' => 'Please select a class.',
            'subject_id.required' => 'Please select a subject.',
            'select_pathern.required' => 'You must select a test pattern.',
            'select_pathern.in' => 'The selected test pattern is invalid.',
        
            // Chapter-wise custom messages
            'chapter_id.required' => 'Please select at least one chapter for the chapter-wise pattern.',
            'chapter_id.array' => 'Chapters must be an array.',
            'chapterWiseMcqs.required' => 'The chapter-wise MCQs input is required.',
            'chapterWiseMcqs.string' => 'The chapter-wise MCQs must be a string.',
        
            // Grand test custom messages
            'numGrandTests.required' => 'Please provide the number of grand tests.',
            'numGrandTests.integer' => 'The number of grand tests must be a number.',
            'questionsPerGrandTest.required' => 'Please specify the number of questions per grand test.',
            'questionsPerGrandTest.integer' => 'The number of questions per grand test must be an integer.',
            'questionsPerSubjectGrandTest.required' => 'You must specify the number of questions per subject for the grand test.',
            'questionsPerSubjectGrandTest.integer' => 'The number of questions per subject for the grand test must be an integer.',
        
            // Mock test custom messages
            'numMockTests.required' => 'Please provide the number of mock tests.',
            'numMockTests.integer' => 'The number of mock tests must be an integer.',
            'questionsPerMockTest.required' => 'Please specify the number of questions per mock test.',
            'questionsPerMockTest.integer' => 'The number of questions per mock test must be an integer.',
            'questionsPerSubjectMockTest.required' => 'Please specify the number of questions per subject for the mock test.',
            'questionsPerSubjectMockTest.integer' => 'The number of questions per subject for the mock test must be an integer.',
        ];
        
        $validatedData = $request->validate($rules, $customMessages);
        
        $mcqs = collect();

        $type = $request->input('select_pathern');

        $subjects = $request->input('subject_id', []);
        $chapters = $request->input('chapter_id', []);
        $numGrandTests = $request->input('numGrandTests', 0);
        $numQuestionsPerTest = $request->input('questionsPerGrandTest', 0);
        $numQuestionsPerSubject = $request->input('questionsPerSubjectGrandTest', 0);
        $numMockTests = $request->input('numMockTests', 0);
        $numQuestionsPerMockTest = $request->input('questionsPerMockTest', 0);
        $questionsPerSubjectMockTest = $request->input('questionsPerSubjectMockTest', 0);
        $countryId = $request->input('country_id', 0); 
        $boardId = $request->input('board_id', 0);
        $classId = $request->input('class_id', 0);

        switch ($type) {
            case 'chapter_wise':
                return $this->generateChapterwisePdf($subjects, $chapters, $request->chapterWiseMcqs, 'book',  $countryId, $boardId, $classId);
                break;

            case 'grand_test':
                return $this->generateGrandTestPdf($numGrandTests, $numQuestionsPerTest, $numQuestionsPerSubject, $subjects, $chapters, 'book', $countryId, $boardId, $classId);
                break;

            case 'mock_test':
                return $this->generateMockTestPdf($numMockTests, $numQuestionsPerMockTest, $questionsPerSubjectMockTest, $subjects, 'book', $countryId, $boardId, $classId);
                break;

            default:
                return response()->json(['success' => false, 'message' => 'Invalid PDF type']);
        }
    }

    // test format 
    public function getTestFormatPdf(Request $request)
    { 
        $rules = [
            'country_id' => 'required',
            'board_id' => 'required',
            'class_id' => 'required',
            'select_pathern' => 'required|in:chapter_wise,grand_test,mock_test',
            'subject_id' => 'required',
        ];
        
        if ($request->select_pathern == 'chapter_wise') {
            $rules['chapter_id'] = 'required|array';
            $rules['chapterWiseMcqs'] = 'required|string';
        }
        
        if ($request->select_pathern == 'grand_test') {
            $rules['chapter_id'] = 'required|array';
            $rules['numGrandTests'] = 'required|integer';
            $rules['questionsPerGrandTest'] = 'required|integer';
            $rules['questionsPerSubjectGrandTest'] = 'required|integer';
        }
        
        if ($request->select_pathern == 'mock_test') {
            $rules['numMockTests'] = 'required|integer';
            $rules['questionsPerMockTest'] = 'required|integer';
            $rules['questionsPerSubjectMockTest'] = 'required|integer';
        }
        
        $customMessages = [
            'country_id.required' => 'Please select a country.',
            'board_id.required' => 'The board selection is required.',
            'class_id.required' => 'Please choose a class.',
            'subject_id.required' => 'The subject field cannot be left blank.',
            'select_pathern.required' => 'Please select a test pattern.',
            'select_pathern.in' => 'Invalid test pattern selected.',
        
            // Custom messages for chapter-wise pattern
            'chapter_id.required' => 'You must select at least one chapter for chapter-wise.',
            'chapter_id.array' => 'The chapters must be a valid array.',
            'chapterWiseMcqs.required' => 'Chapter-wise MCQs input is required.',
            'chapterWiseMcqs.string' => 'The Chapter-wise MCQs must be a string.',
        
            // Custom messages for grand test pattern
            'numGrandTests.required' => 'Please provide the number of grand tests.',
            'numGrandTests.integer' => 'The number of grand tests must be a valid number.',
            'questionsPerGrandTest.required' => 'Please specify the number of questions per grand test.',
            'questionsPerGrandTest.integer' => 'The number of questions per grand test must be an integer.',
            'questionsPerSubjectGrandTest.required' => 'You must specify the number of questions per subject for the grand test.',
            'questionsPerSubjectGrandTest.integer' => 'The number of questions per subject for the grand test must be a valid number.',
        
            // Custom messages for mock test pattern
            'numMockTests.required' => 'Please provide the number of mock tests.',
            'numMockTests.integer' => 'The number of mock tests must be a valid number.',
            'questionsPerMockTest.required' => 'Please specify the number of questions per mock test.',
            'questionsPerMockTest.integer' => 'The number of questions per mock test must be an integer.',
            'questionsPerSubjectMockTest.required' => 'Please specify the number of questions per subject for the mock test.',
            'questionsPerSubjectMockTest.integer' => 'The number of questions per subject for the mock test must be a valid number.',
        ];
        
        $validatedData = $request->validate($rules, $customMessages);
        $mcqs = collect();

        $type = $request->input('select_pathern');

        $subjects = $request->input('subject_id', []);
        $chapters = $request->input('chapter_id', []);
        $numGrandTests = $request->input('numGrandTests', 0);
        $numQuestionsPerTest = $request->input('questionsPerGrandTest', 0);
        $numQuestionsPerSubject = $request->input('questionsPerSubjectGrandTest', 0);
        $numMockTests = $request->input('numMockTests', 0);
        $numQuestionsPerMockTest = $request->input('questionsPerMockTest', 0);
        $questionsPerSubjectMockTest = $request->input('questionsPerSubjectMockTest', 0);
        
        $countryId = $request->input('country_id', 0); 
        $boardId = $request->input('board_id', 0);
        $classId = $request->input('class_id', 0);

        switch ($type) {
            case 'chapter_wise':
                return $this->generateChapterwisePdf($subjects, $chapters, $request->chapterWiseMcqs, 'test', $countryId, $boardId, $classId);
                break;

            case 'grand_test':
                return $this->generateGrandTestPdf($numGrandTests, $numQuestionsPerTest, $numQuestionsPerSubject, $subjects, $chapters, 'test', $countryId, $boardId, $classId);
                break;

            case 'mock_test':
                return $this->generateMockTestPdf($numMockTests, $numQuestionsPerMockTest, $questionsPerSubjectMockTest, $subjects, 'test', $countryId, $boardId, $classId);
                break;

            default:
                return response()->json(['success' => false, 'message' => 'Invalid PDF type']);
        }
    }

    private function generateChapterwisePdf($subject, $chapters, $takeMcqs, $format, $countryId, $boardId, $classId)
    {
        // Fetch MCQs based on the selected subjects and chapters
        $subject = Subject::find($subject);
        $mcqs = $this->getMCQsForChapters($subject, $chapters, $takeMcqs, $countryId, $boardId, $classId);

        if ($mcqs && collect($mcqs)->filter()->isNotEmpty()) {
            $mcqs = collect($mcqs)->map(function ($subject) {
                return collect($subject)->map(function ($chapter) {
                    return $chapter->map(function ($mcq) {
                        $mcq->statement = $this->convertMathTexToImage($mcq->statement);
                        $mcq->qr_code_english = $mcq->solution_link_english ? $this->generateQrCodeImage($mcq->solution_link_english) : null;
                        $mcq->qr_code_urdu = $mcq->solution_link_urdu ? $this->generateQrCodeImage($mcq->solution_link_urdu) : null;
                        return $mcq;
                    });
                });
            });
        
            // Ensure there's still data after mapping
            if ($mcqs->flatten(3)->isEmpty()) {
                return response()->json(['success' => false, 'message' => 'No MCQs found after processing.']);
            }
            if($format == 'book'){
                $pdf = PDF::loadView('mcqs-question.pdf.chapterwise', compact('subject', 'chapters', 'mcqs'));
            } else if ($format == 'test')
            {
                $pdf = PDF::loadView('mcqs-question.test-format.chapterwise', compact('subject', 'chapters', 'mcqs'));

            }
            $directoryPath = storage_path('app/public/mcqs-pdfs');
        
            if (File::exists($directoryPath)) {
                File::cleanDirectory($directoryPath);
            } else {
                File::makeDirectory($directoryPath, 0755, true);
            }
        
            $fileName = 'mcqs-' . time() . '.pdf';
            $filePath = $directoryPath . '/' . $fileName;
            $pdf->save($filePath);
        
            $pdfUrl = Storage::url('mcqs-pdfs/' . $fileName);
        
            return response()->json(['success' => true, 'pdf_url' => $pdfUrl]);
        } else {
            return response()->json(['success' => false, 'message' => 'No MCQs found for the selected criteria.']);
        }
    }

    private function generateGrandTestPdf($numGrandTests, $numQuestionsPerTest, $numQuestionsPerSubject, $subjects, $chapters, $format, $countryId, $boardId, $classId)
    {
        // Fetch MCQs based on the selected subjects and chapters
        $mcqs = $this->getMCQsForGrandTest($subjects, $chapters, $numGrandTests, $numQuestionsPerTest, $numQuestionsPerSubject, $countryId, $boardId, $classId);
        // dd($mcqs);
        // if ($mcqs && collect($mcqs)->filter()->isNotEmpty()) {
        //     $mcqs = collect($mcqs)->map(function ($subject) {
        //         return collect($subject)->map(function ($chapter) {
        //             return $chapter->map(function ($mcq) {
        //                 $mcq->statement = $this->convertMathTexToImage($mcq->statement);
        //                 $mcq->qr_code_english = $mcq->solution_link_english ? $this->generateQrCodeImage($mcq->solution_link_english) : null;
        //                 $mcq->qr_code_urdu = $mcq->solution_link_urdu ? $this->generateQrCodeImage($mcq->solution_link_urdu) : null;
        //                 return $mcq;
        //             });
        //         });
        //     });
        
        //     // Ensure there's still data after mapping
        //     if ($mcqs->flatten(3)->isEmpty()) {
        //         return response()->json(['success' => false, 'message' => 'No MCQs found after processing.']);
        //     }
        if ($mcqs) {
            // Filter out empty arrays
            $mcqs = collect($mcqs)->filter(function ($subject) {
                return collect($subject)->isNotEmpty();
            });
        
            // If after filtering there are no MCQs, return a failure response
            if ($mcqs->isEmpty()) {
                return response()->json(['success' => false, 'message' => 'No MCQs found for the selected criteria.']);
            }
        
            // Process MCQs
            $mcqs = $mcqs->map(function ($subject) {
                return collect($subject)->map(function ($chapter) {
                    return $chapter->map(function ($mcq) {
                        $mcq->statement = $this->convertMathTexToImage($mcq->statement);
                        $mcq->qr_code_english = $mcq->solution_link_english ? $this->generateQrCodeImage($mcq->solution_link_english) : null;
                        $mcq->qr_code_urdu = $mcq->solution_link_urdu ? $this->generateQrCodeImage($mcq->solution_link_urdu) : null;
                        return $mcq;
                    });
                });
            });
            if($format == 'book'){
                $pdf = PDF::loadView('mcqs-question.pdf.grandtest', compact('numGrandTests', 'numQuestionsPerTest', 'numQuestionsPerSubject', 'subjects', 'chapters', 'mcqs'));
            } else if ($format == 'test')
            {
                // Generate PDF
                $pdf = PDF::loadView('mcqs-question.test-format.grandtest', compact('numGrandTests', 'numQuestionsPerTest', 'numQuestionsPerSubject', 'subjects', 'chapters', 'mcqs'));

            }
        
            $directoryPath = storage_path('app/public/mcqs-pdfs');
        
            if (File::exists($directoryPath)) {
                File::cleanDirectory($directoryPath);
            } else {
                File::makeDirectory($directoryPath, 0755, true);
            }
        
            $fileName = 'mcqs-' . time() . '.pdf';
            $filePath = $directoryPath . '/' . $fileName;
            $pdf->save($filePath);
        
            $pdfUrl = Storage::url('mcqs-pdfs/' . $fileName);
        
            return response()->json(['success' => true, 'pdf_url' => $pdfUrl]);
        } else {
            return response()->json(['success' => false, 'message' => 'No MCQs found for the selected criteria.']);
        }
    }

    private function generateMockTestPdf($numMockTests, $numQuestionsPerMockTest, $questionsPerSubjectMockTest, $subjects, $format, $countryId, $boardId, $classId)
    {
        // Fetch MCQs based on the selected subjects
        $mcqs = $this->getMCQsForMockTest($subjects, $numMockTests, $numQuestionsPerMockTest, $questionsPerSubjectMockTest, $countryId, $boardId, $classId);
        // dd($mcqs);
        if ($mcqs) {
            // Filter out empty arrays
            $mcqs = collect($mcqs)->filter(function ($subject) {
                return collect($subject)->isNotEmpty();
            });
        
            // If after filtering there are no MCQs, return a failure response
            if ($mcqs->isEmpty()) {
                return response()->json(['success' => false, 'message' => 'No MCQs found for the selected criteria.']);
            }
        
            // Process MCQs
            $mcqs = $mcqs->map(function ($subject) {
                return collect($subject)->map(function ($chapter) {
                    return $chapter->map(function ($mcq) {
                        $mcq->statement = $this->convertMathTexToImage($mcq->statement);
                        $mcq->qr_code_english = $mcq->solution_link_english ? $this->generateQrCodeImage($mcq->solution_link_english) : null;
                        $mcq->qr_code_urdu = $mcq->solution_link_urdu ? $this->generateQrCodeImage($mcq->solution_link_urdu) : null;
                        return $mcq;
                    });
                });
            });
            
            // Flatten structure for easier processing in the view
            $flattenedMcqs = [];

            if($format == 'book'){
                // Generate PDF
                $pdf = PDF::loadView('mcqs-question.pdf.mocktest', compact('numMockTests', 'numQuestionsPerMockTest', 'questionsPerSubjectMockTest', 'subjects', 'flattenedMcqs', 'mcqs'));
            } else if ($format == 'test')
            {
                // Generate PDF
                $pdf = PDF::loadView('mcqs-question.test-format.mocktest', compact('numMockTests', 'numQuestionsPerMockTest', 'questionsPerSubjectMockTest', 'subjects', 'flattenedMcqs', 'mcqs'));

            }
            
            $directoryPath = storage_path('app/public/mcqs-pdfs');
        
            if (File::exists($directoryPath)) {
                File::cleanDirectory($directoryPath);
            } else {
                File::makeDirectory($directoryPath, 0755, true);
            }
        
            $fileName = 'mcqs-' . time() . '.pdf';
            $filePath = $directoryPath . '/' . $fileName;
            $pdf->save($filePath);
        
            $pdfUrl = Storage::url('mcqs-pdfs/' . $fileName);
        
            return response()->json(['success' => true, 'pdf_url' => $pdfUrl]);
        } else {
            return response()->json(['success' => false, 'message' => 'No MCQs found for the selected criteria.']);
        }
    }

    

    private function getMCQsForChapters($subject, $chapters, $takeMcqs, $countryId, $boardId, $classId)
    {
        $mcqs = [];
        $usedMcqIds = []; // Track already selected MCQ IDs
         
        if ($subject) {
            foreach ($chapters as $chapterId) {
                $chapter = Chapter::with(['subjects' => function ($q) use ($subject) {
                        $q->where('subject_id', $subject->id);
                    }])
                    ->where('id', $chapterId)
                    ->first();
        
                // Ensure the chapter is found and has the related subject
                if ($chapter && $chapter->subjects->isNotEmpty()) {
                    // Check if the current chapter has the specific subject
                    $subjectRelation = $chapter->subjects->first(); // Get the first matching subject
                    if ($subjectRelation->id == $subject->id) {
                        // Fetch MCQs for the chapter and exclude previously used MCQs
                        $chapterMcqs = Mcqs::with('subjects', 'chapters', 'countries', 'boards', 'classes')
                            ->whereHas('subjects', function($q) use ($subject) {
                                $q->where('subjects.id', $subject->id);
                            })
                            ->whereHas('chapters', function($q) use ($chapter) {
                                $q->where('chapters.id', $chapter->id);
                            })
                            ->whereHas('countries', function ($q) use ($countryId) {
                                $q->where('countries.id', $countryId);
                            })
                            ->whereHas('boards', function ($q) use ($boardId) {
                                $q->where('boards.id', $boardId);
                            })
                            ->whereHas('classes', function ($q) use ($classId) {
                                $q->where('classroom_id', $classId); // Correct reference to 'classroom_id'
                            })
                            ->whereNotIn('id', $usedMcqIds) // Exclude already used MCQs
                            ->inRandomOrder()
                            ->take($takeMcqs)
                            ->get();
        
                        // Add the new MCQs to the list and track their IDs
                        $mcqs[$chapter->name][$subject->name] = $chapterMcqs;
        
                        // Add the selected MCQ IDs to the array to avoid duplication
                        $usedMcqIds = array_merge($usedMcqIds, $chapterMcqs->pluck('id')->toArray());
                    }
                }
            }
        }
        
        return $mcqs;
    }

    private function getMCQsForGrandTest($subjects, $chapters, $numGrandTests, $numQuestionsPerTest, $numQuestionsPerSubject, $countryId, $boardId, $classId)
    {
        $allMCQs = [];
        $usedMcqIds = [];

        foreach ($subjects as $subjectId) {
            $subject = Subject::find($subjectId);
            if ($subject) {
                $subjectMCQs = collect();
                foreach ($chapters as $chapterId) {
                    $chapter = Chapter::with(['subjects' => function ($q) use ($subject) {
                            $q->where('subject_id', $subject->id);
                        }])
                        ->where('id', $chapterId)
                        ->first();
            
                    // Ensure the chapter is found and has the related subject
                    if ($chapter && $chapter->subjects->isNotEmpty()) {
                        $subjectRelation = $chapter->subjects->first(); // Get the first matching subject
                        if ($subjectRelation->id == $subject->id) {
                            $questions = Mcqs::with('subjects', 'chapters', 'countries', 'boards', 'classes')
                            ->whereHas('subjects', function($q) use ($subject) {
                                $q->where('subjects.id', $subject->id);
                            })
                            ->whereHas('chapters', function($q) use ($chapter) {
                                $q->where('chapters.id', $chapter->id);
                            })
                            ->whereHas('countries', function ($q) use ($countryId) {
                                $q->where('countries.id', $countryId);
                            })
                            ->whereHas('boards', function ($q) use ($boardId) {
                                $q->where('boards.id', $boardId);
                            })
                            ->whereHas('classes', function ($q) use ($classId) {
                                $q->where('classroom_id', $classId); // Correct reference to 'classroom_id'
                            })
                            ->whereNotIn('id', $usedMcqIds)
                            ->inRandomOrder()
                            ->take($numQuestionsPerSubject)
                            ->get();   

                            $subjectMCQs = $subjectMCQs->concat($questions);
                            $usedMcqIds = array_merge($usedMcqIds, $questions->pluck('id')->toArray());
                    
                            if ($subjectMCQs->count() > $numQuestionsPerSubject) {
                                $subjectMCQs = $subjectMCQs->random($numQuestionsPerSubject);
                            }
                            
                            $allMCQs[$subject->name] = $subjectMCQs;
                        }
                    }
                }
            }
        }
        $grandTests = [];
        // for ($i = 0; $i < $numGrandTests; $i++) {
        //     $grandTests[$i] = [];
            
        //     foreach ($allMCQs as $subjectName => $questions) {
        //         if ($questions->count() >= $numQuestionsPerTest) {
        //             $questionsForTest = $questions->random($numQuestionsPerTest);
                    
        //             $grandTests[$i][$subjectName] = $questionsForTest;
                    
        //             $allMCQs[$subjectName] = $allMCQs[$subjectName]->diff($questionsForTest);
        //         } else {
        //             $grandTests[$i][$subjectName] = $questions;
                    
        //             $allMCQs[$subjectName] = $allMCQs[$subjectName]->diff($questions);
        //         }
        //     }
        // }

        for ($i = 0; $i < $numGrandTests; $i++) {
            $grandTests[$i] = [];
            
            foreach ($allMCQs as $subjectName => $questions) {
                $availableQuestions = $questions->count();
                
                if ($availableQuestions > 0) {
                    $questionsForTest = $availableQuestions >= $numQuestionsPerTest
                        ? $questions->random($numQuestionsPerTest)
                        : $questions;
                    
                    // Store the selected questions
                    $grandTests[$i][$subjectName] = $questionsForTest;
                    
                    // Remove the selected questions from the pool to avoid repetition
                    $allMCQs[$subjectName] = $allMCQs[$subjectName]->diff($questionsForTest);
                }
            }
        }
        return $grandTests;
    }

    private function getMCQsForMockTest($subjects, $numMockTests, $numQuestionsPerMockTest, $numQuestionsPerSubject, $countryId, $boardId, $classId)
    {
        $allMCQs = [];
        $usedMcqIds = [];
        
        foreach ($subjects as $subjectId) {
            $subject = Subject::find($subjectId);
            if ($subject) {
                $subjectMCQs = collect();
                $questions = Mcqs::with('subjects', 'chapters')
                        ->whereHas('subjects', function($q) use ($subject) {
                            $q->where('subjects.id', $subject->id);
                        })
                        ->whereHas('countries', function ($q) use ($countryId) {
                            $q->where('countries.id', $countryId);
                        })
                        ->whereHas('boards', function ($q) use ($boardId) {
                            $q->where('boards.id', $boardId);
                        })
                        ->whereHas('classes', function ($q) use ($classId) {
                            $q->where('classroom_id', $classId); // Correct reference to 'classroom_id'
                        })
                        ->whereNotIn('id', $usedMcqIds)
                        ->inRandomOrder()
                        ->take($numQuestionsPerSubject)
                        ->get();
                        $subjectMCQs = $subjectMCQs->concat($questions);
                        $usedMcqIds = array_merge($usedMcqIds, $questions->pluck('id')->toArray());
                
                if ($subjectMCQs->count() > $numQuestionsPerSubject) {
                    $subjectMCQs = $subjectMCQs->random($numQuestionsPerSubject);
                }
                
                $allMCQs[$subject->name] = $subjectMCQs;
            }
        }

        $mockTests = [];  

        for ($i = 0; $i < $numMockTests; $i++) {
            $mockTests[$i] = [];
            
            foreach ($allMCQs as $subjectName => $questions) {
                // Ensure we don't try to take more questions than are available
                $availableQuestions = $questions->count();
                
                if ($availableQuestions > 0) {
                    $questionsForTest = $availableQuestions >= $numQuestionsPerMockTest
                        ? $questions->random($numQuestionsPerMockTest)
                        : $questions;
                    
                    // Store the selected questions
                    $mockTests[$i][$subjectName] = $questionsForTest;
                    
                    // Remove the selected questions from the pool to avoid repetition
                    $allMCQs[$subjectName] = $allMCQs[$subjectName]->diff($questionsForTest);
                }
            }
        }
        
        return $mockTests;
        
    }

    function convertMathTexToImage($html) {
        // Load the HTML into DOMDocument
        $dom = new \DOMDocument();
        @$dom->loadHTML($html, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
        
        // Create an XPath to search for elements with class 'math-tex'
        $xpath = new \DOMXPath($dom);
        $nodes = $xpath->query("//*[contains(@class, 'math-tex')]");
    
        foreach ($nodes as $node) {
            // Get the LaTeX content from the node
            $latexContent = $node->nodeValue;
    
            // URL encode the LaTeX formula for safe transmission
            $encodedLatex = urlencode($latexContent);
    
            $img = $dom->createElement('img');
            $img->setAttribute('src', "https://latex.codecogs.com/png.latex?$encodedLatex");
            $img->setAttribute('alt', 'LaTeX Formula');
            $img->setAttribute('style', 'display: inline;');  // Add styles if needed

            // Replace the span tag with the generated image
            $node->parentNode->replaceChild($img, $node);
        }
    
        // Save and return the modified HTML
        // dd($dom->saveHTML());
        return $dom->saveHTML();
    }

}
