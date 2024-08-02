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
            $query = MCQs::with(['country', 'board', 'class', 'subject', 'chapter', 'topic']);
    
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
                    return $row->country ? $row->country->name : '';
                })
                ->addColumn('board_name', function ($row) {
                    return $row->board ? $row->board->name : '';
                })
                ->addColumn('class_name', function ($row) {
                    return $row->class ? $row->class->name : '';
                })
                ->addColumn('subject_name', function ($row) {
                    return $row->subject ? $row->subject->name : '';
                })
                ->addColumn('chapter_name', function ($row) {
                    return $row->chapter ? $row->chapter->name : '';
                })
                ->addColumn('topic_name', function ($row) {
                    return $row->topic ? $row->topic->name : '';
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
                ->rawColumns(['solution_link_english', 'solution_link_urdu', 'actions'])
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

    public function getboards($country_id)
    {
        $boards = Board::whereHas('countries', function ($query) use ($country_id) {
            $query->where('country_id', $country_id);
        })->get();

        return response()->json($boards);
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
        $subjectIds = explode(',', $subject_id);
        $chapters = [];
        
        if (is_array($subjectIds) && count($subjectIds) > 0) {
            $subjects = Subject::whereIn('id', $subjectIds)->with('chapters')->get();
            
            foreach ($subjects as $subject) {
                $chapters = array_merge($chapters, $subject->chapters->toArray());
            }
        } else {
            $subject = Subject::with('chapters')->find($subject_id);
            if ($subject) {
                $chapters = $subject->chapters->toArray();
            }
        }

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
        ]);

        $mcq = new MCQs();
        $mcq->country_id = $request->country_id;
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
        $mcq = MCQs::with('country', 'board', 'class', 'subject', 'chapter', 'topic')->find($mcq->id);

        $pageHead = 'Edit MCQs';
        $pageTitle = 'Edit MCQs';
        $activeMenu = 'MCQs';

        // Fetch all boards, classes, subjects, chapters, and topics to populate the dropdowns
        $countries = Country::all();
        $boards = $mcq->country ? $mcq->country->board : collect();
        $classes = $mcq->board ? $mcq->board->classrooms : collect();
        $subjects = $mcq->class ? $mcq->class->subjects : collect();
        $chapters = $mcq->subject ? $mcq->subject->chapters : collect();
        $topics = $mcq->chapter ? $mcq->chapter->topics : collect();

        return view('mcqs-question.edit', compact('activeMenu', 'pageHead', 'pageTitle', 'mcq', 'countries', 'boards', 'classes', 'subjects', 'chapters', 'topics'));
    }


    public function update(Request $request, MCQs $mcq)
    {
        $validated = $request->validate([
            'country_id' => 'required',
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
        } catch (\Exception $ex) {
            // Return a 500 Internal Server Error response if an error occurs
            return response()->json(['error' => 'Internal Server Error'], 500);
        }
    }

    public function downloadSingleMcqAsPptx($id)
    {
        $mcq = Mcqs::findOrFail($id);
    
        // Create a new PHPPresentation object
        $objPHPPresentation = new PhpPresentation();
    
        // Remove the default slide
        $objPHPPresentation->removeSlideByIndex(0);
    
        // Create a new slide
        $currentSlide = $objPHPPresentation->createSlide();
    
        // Add question text
        $questionShape = $currentSlide->createRichTextShape()
            ->setHeight(100)
            ->setWidth(600)
            ->setOffsetX(50)
            ->setOffsetY(50);
        $questionShape->getActiveParagraph()->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $textRun = $questionShape->createTextRun(str_replace('&nbsp;', ' ', $this->stripHtmlTags($mcq->statement)));
        $textRun->getFont()->setBold(true)
            ->setSize(18)
            ->setColor(new Color('FF000000'));
    
        // Add options
        $options = [
            'A' => $this->stripHtmlTags($mcq->optionA),
            'B' => $this->stripHtmlTags($mcq->optionB),
            'C' => $this->stripHtmlTags($mcq->optionC),
            'D' => $this->stripHtmlTags($mcq->optionD)
        ];
        $offsetY = 150;
        foreach ($options as $key => $value) {
            $optionShape = $currentSlide->createRichTextShape()
                ->setHeight(50)
                ->setWidth(600)
                ->setOffsetX(50)
                ->setOffsetY($offsetY);
            $textRun = $optionShape->createTextRun("{$key}) {$value}");
            $textRun->getFont()->setSize(16)
                ->setColor(new Color('FF000000'));
            $offsetY += 60;
        }
    
        // Generate QR codes for solution links
        $qrCodeOffsetY = $offsetY + 50;
        if (!empty($mcq->solution_link_english)) {
            $this->addQrCodeToSlide($currentSlide, $mcq->solution_link_english, 'English Solution', 50, $qrCodeOffsetY);
        }
        if (!empty($mcq->solution_link_urdu)) {
            $this->addQrCodeToSlide($currentSlide, $mcq->solution_link_urdu, 'Urdu Solution', 200, $qrCodeOffsetY); 
        }
    
        // Save the presentation to a temporary file
        $pptxFile = storage_path('app/public/mcq_' . $id . '.pptx');
        $objWriter = IOFactory::createWriter($objPHPPresentation, 'PowerPoint2007');
        $objWriter->save($pptxFile);
    
        // Return the file as a download response
        return response()->download($pptxFile)->deleteFileAfterSend(true);
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
        return strip_tags($content);
    }

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
        
    }

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
            $rules['grandTestChapters'] = 'required|array';
            $rules['grandTestTopics'] = 'required';
            $rules['numGrandTests'] = 'required|integer';
            $rules['questionsPerGrandTest'] = 'required|integer';
            $rules['questionsPerSubjectGrandTest'] = 'required|integer';
        }
    
        if ($request->select_pathern == 'mock_test') {
            $rules['numMockTests'] = 'required|integer';
            $rules['questionsPerMockTest'] = 'required|integer';
            $rules['questionsPerSubjectMockTest'] = 'required|integer';
        }
    
        $validatedData = $request->validate($rules);
        $mcqs = collect();

        $type = $request->input('select_pathern');

        $subjects = $request->input('subject_id', []);
        $chapters = $request->input('chapter_id', []);
        $numGrandTests = $request->input('num_grand_tests', 0);
        $numQuestionsPerTest = $request->input('num_questions_per_test', 0);
        $numQuestionsPerSubject = $request->input('num_questions_per_subject', 0);
        $numMockTests = $request->input('num_mock_tests', 0);
        $numQuestionsPerMockTest = $request->input('num_questions_per_mock_test', 0);

        switch ($type) {
            case 'chapter_wise':
                return $this->generateChapterwisePdf($subjects, $chapters, $request->chapterWiseMcqs);
                break;

            case 'grand_test':
                return $this->generateGrandTestPdf($numGrandTests, $numQuestionsPerTest, $numQuestionsPerSubject, $subjects, $chapters);
                break;

            case 'mock_test':
                return $this->generateMockTestPdf($numMockTests, $numQuestionsPerMockTest, $numQuestionsPerSubject, $subjects);
                break;

            default:
                return response()->json(['success' => false, 'message' => 'Invalid PDF type']);
        }

        // if ($mcqs) {
        //     $mcqs = collect($mcqs)->map(function ($subject) {
        //         return collect($subject)->map(function ($chapter) {
        //             return $chapter->map(function ($mcq) {
        //                 $mcq->qr_code_english = $mcq->solution_link_english ? $this->generateQrCodeImage($mcq->solution_link_english) : null;
        //                 $mcq->qr_code_urdu = $mcq->solution_link_urdu ? $this->generateQrCodeImage($mcq->solution_link_urdu) : null;
        //                 return $mcq;
        //             });
        //         });
        //     });

        //     $pdf = PDF::loadView('mcqs-question.mcqs_book_format_pdf', compact('mcqs'));

        //     $directoryPath = storage_path('app/public/mcqs-pdfs');

        //     if (File::exists($directoryPath)) {
        //         File::cleanDirectory($directoryPath);
        //     } else {
        //         File::makeDirectory($directoryPath, 0755, true);
        //     }

        //     $fileName = 'mcqs-' . time() . '.pdf';
        //     $filePath = $directoryPath . '/' . $fileName;
        //     $pdf->save($filePath);

        //     $pdfUrl = Storage::url('mcqs-pdfs/' . $fileName);

        //     return response()->json(['success' => true, 'pdf_url' => $pdfUrl]);
        // } else {
        //     return response()->json(['success' => false]);
        // }
    }



    private function generateChapterwisePdf($subject, $chapters, $takeMcqs)
    {
        // Fetch MCQs based on the selected subjects and chapters
        $subject = Subject::find($subject);
        $mcqs = $this->getMCQsForChapters($subject, $chapters, $takeMcqs);
        if ($mcqs) {
            $mcqs = collect($mcqs)->map(function ($subject) {
                return collect($subject)->map(function ($chapter) {
                    return $chapter->map(function ($mcq) {
                        $mcq->qr_code_english = $mcq->solution_link_english ? $this->generateQrCodeImage($mcq->solution_link_english) : null;
                        $mcq->qr_code_urdu = $mcq->solution_link_urdu ? $this->generateQrCodeImage($mcq->solution_link_urdu) : null;
                        return $mcq;
                    });
                });
            });
            $pdf = PDF::loadView('mcqs-question.pdf.chapterwise', compact('subject', 'chapters', 'mcqs'));
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
            return response()->json(['success' => false]);
        }
    }

    private function generateGrandTestPdf($numGrandTests, $numQuestionsPerTest, $numQuestionsPerSubject, $subjects, $chapters)
    {
        // Fetch MCQs based on the selected subjects and chapters
        $mcqs = $this->getMCQsForGrandTest($subjects, $chapters, $numQuestionsPerTest, $numQuestionsPerSubject);

        if ($mcqs) {
            $mcqs = collect($mcqs)->map(function ($subject) {
                return collect($subject)->map(function ($chapter) {
                    return $chapter->map(function ($mcq) {
                        $mcq->qr_code_english = $mcq->solution_link_english ? $this->generateQrCodeImage($mcq->solution_link_english) : null;
                        $mcq->qr_code_urdu = $mcq->solution_link_urdu ? $this->generateQrCodeImage($mcq->solution_link_urdu) : null;
                        return $mcq;
                    });
                });
            });

        
           $pdf = PDF::loadView('pdf.grandtest', compact('numGrandTests', 'numQuestionsPerTest', 'numQuestionsPerSubject', 'subjects', 'chapters', 'mcqs'));

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
            return response()->json(['success' => false]);
        }
    }

    private function generateMockTestPdf($numMockTests, $numQuestionsPerMockTest, $numQuestionsPerSubject, $subjects)
    {
        // Fetch MCQs based on the selected subjects
        $mcqs = $this->getMCQsForMockTest($subjects, $numQuestionsPerMockTest, $numQuestionsPerSubject);
        if ($mcqs) {
            $mcqs = collect($mcqs)->map(function ($subject) {
                return collect($subject)->map(function ($chapter) {
                    return $chapter->map(function ($mcq) {
                        $mcq->qr_code_english = $mcq->solution_link_english ? $this->generateQrCodeImage($mcq->solution_link_english) : null;
                        $mcq->qr_code_urdu = $mcq->solution_link_urdu ? $this->generateQrCodeImage($mcq->solution_link_urdu) : null;
                        return $mcq;
                    });
                });
            });

            $pdf = PDF::loadView('pdf.mocktest', compact('numMockTests', 'numQuestionsPerMockTest', 'numQuestionsPerSubject', 'subjects', 'mcqs'));

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
            return response()->json(['success' => false]);
        }
    }

    

    private function getMCQsForChapters($subject, $chapters, $takeMcqs)
    {
        $mcqs = [];
        if ($subject) {
            foreach ($chapters as $chapterId) {
                $chapter = Chapter::find($chapterId);
                if ($chapter && $chapter->subject_id == $subject->id) {
                    $mcqs[$subject->name][$chapter->name] = Mcqs::where('subject_id', $subject->id)->where('chapter_id', $chapterId)->inRandomOrder()->take($takeMcqs)->get();
                }
            }
        }
        return $mcqs;
    }

    private function getMCQsForGrandTest($subjects, $chapters, $numQuestionsPerTest, $numQuestionsPerSubject)
    {
        $mcqs = [];
        
        $totalQuestions = [];

        foreach ($subjects as $subjectId) {
            $subject = Subject::find($subjectId);
            if ($subject) {
                $mcqs[$subject->name] = [];

                foreach ($chapters as $chapterId) {
                    $chapter = Chapter::find($chapterId);
                    if ($chapter && $chapter->subject_id == $subjectId) {
                        $questions = Mcqs::where('subject_id', $subjectId)
                            ->where('chapter_id', $chapterId)
                            ->inRandomOrder()
                            ->take($numQuestionsPerSubject)
                            ->get();

                        $mcqs[$subject->name][$chapter->name] = $questions;

                        if (!isset($totalQuestions[$subject->name])) {
                            $totalQuestions[$subject->name] = collect();
                        }
                        $totalQuestions[$subject->name] = $totalQuestions[$subject->name]->concat($questions);
                    }
                }
            }
        }

        foreach ($mcqs as $subjectName => $chapters) {
            $subjectTotalQuestions = $totalQuestions[$subjectName];
            if ($subjectTotalQuestions->count() > $numQuestionsPerTest) {
                $mcqs[$subjectName] = $subjectTotalQuestions->random($numQuestionsPerTest);
            }
        }

        return $mcqs;
    }

    private function getMCQsForMockTest($subjects, $numQuestionsPerMockTest, $numQuestionsPerSubject)
    {
        $mcqs = [];
        foreach ($subjects as $subjectId) {
            $subject = Subject::find($subjectId);
            if ($subject) {
                $mcqs[$subject->name] = Mcqs::whereHas('chapter', function ($query) use ($subjectId) {
                    $query->where('subject_id', $subjectId);
                })->inRandomOrder()->take($numQuestionsPerMockTest)->get();
            }
        }
        return $mcqs;
    }
}
