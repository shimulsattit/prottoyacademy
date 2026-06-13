<?php 

namespace App\Services;

use App\Repositories\Interface\CategoryRepositoryInterface;
use Illuminate\Support\Facades\Validator;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Yajra\DataTables\Facades\DataTables;
use App\Repositories\Interface\QuestionRepositoryInterface;
use PhpOffice\PhpSpreadsheet\Settings;

class QuestionService {

    protected $questionRepository;
    protected $categoryRepository;

    public function __construct(
        QuestionRepositoryInterface $questionRepository,
        CategoryRepositoryInterface $categoryRepository
    ) {
        $this->questionRepository = $questionRepository;
        $this->categoryRepository = $categoryRepository;
    }

    public function all()
    {
        return $this->questionRepository->all();
    }

    public function dataTable($categoryId = null)
    {
        if($categoryId) {
            $categoryIds = $this->categoryRepository->getAllCategoryIds($categoryId);
            $models = $this->all()->whereIn('category_id', $categoryIds);
        } else {
            $models = $this->all();
        }
        return DataTables::of($models)
            ->addIndexColumn()
            ->addColumn('action', function ($model) {
                return view('portal.question.action', compact('model'));
            })
            ->editColumn('question', function ($model) {
                return html_entity_decode($model->question);
            })
            ->editColumn('status', function ($model) {
                if($model->status == 1) {
                    $status = '<span class="badge badge-success">Publish</span>';
                } else {
                    $status = '<span class="badge badge-warning">Unpublish</span>';
                }

                return $status;
            })
            ->editColumn('category', function($model) {
                return $model->category ? $model->category->name : '';
            })
            ->editColumn('created_by', function($model) {
                return $model->admin ? $model->admin->first_name . ' '. $model->admin->last_name : '';
            })
            ->editColumn('created_at', function($model) {
                return date('d F, Y h:i A', strtotime($model->created_at));
            })
            ->rawColumns(['action', 'question', 'created_by', 'category', 'status', 'created_at'])
            ->make(true);
    }

    public function binDataTable()
    {
        $models = $this->questionRepository->onlyTrashed();
        return DataTables::of($models)
            ->addIndexColumn()
            ->addColumn('action', function ($model) {
                return view('portal.question.bin-action', compact('model'));
            })
            ->editColumn('created_by', function($model) {
                return $model->admin ? $model->admin->first_name . ' '. $model->admin->last_name : '';
            })
            ->editColumn('deleted_at', function($model) {
                return date('d F, Y h:i A', strtotime($model->deleted_at));
            })
            ->editColumn('created_at', function($model) {
                return date('d F, Y h:i A', strtotime($model->created_at));
            })
            ->rawColumns(['action', 'deleted_at', 'created_by', 'category', 'status', 'created_at'])
            ->make(true);
    }

    public function findById($id)
    {
        return $this->questionRepository->getById($id);
    }

    public function find($id)
    {
        return $this->questionRepository->getByUUId($id);
    }

    public function import($request) 
    {
        $validator = Validator::make($request->all(), [
            'file'              => 'required|file|mimes:csv,xlsx,xls|max:2048',
            'category_id'       => 'required',
            'job_category_id'   => 'nullable|integer|exists:job_categories,id',
            'year_id'           => 'nullable|integer|exists:years,id',
            'exam_id'           => 'nullable|integer|exists:exams,id',
            'passage_id'        => 'nullable|integer|exists:passages,id',
            'question_type'     => 'required|string|in:mcq,cq,short_answer,long_answer,true_false, fill_in_the_blanks,matching',
            'is_math'           => 'nullable|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'validator' => true,
                'message' => $validator->errors()
            ]);
        }

        Settings::setLibXmlLoaderOptions(LIBXML_DTDLOAD | LIBXML_DTDATTR | LIBXML_NOENT);

        $file = $request->file('file');
        $spreadsheet = IOFactory::load($file->getRealPath());
        $sheet = $spreadsheet->getActiveSheet();
        $rows = $sheet->toArray();

        // Check if the target category is under Current Affairs or Academy
        $catId = is_array($request->category_id) ? $request->category_id[count($request->category_id) - 1] : $request->category_id;
        $category = \App\Models\Category::find($catId);
        $isCurrentAffairs = false;
        $isAcademy = false;
        if ($category) {
            $breadcrumbs = $category->breadcrumb();
            $isCurrentAffairs = $breadcrumbs->contains('slug', 'current-affairs');
            $isAcademy = $breadcrumbs->contains('slug', 'academy');
        }

        // Normalize Current Affairs short questions:
        // Excel layout: A = Sub Sub Category, B = Question, C = Answer
        // Convert to standard layout: A = Question, B = Answer, C = Sub Sub Category
        if ($isCurrentAffairs && $request->question_type != 'mcq') {
            foreach ($rows as $key => &$row) {
                if ($key === 0) continue;
                
                $subSubCategory = isset($row[0]) ? trim($row[0]) : '';
                $question = isset($row[1]) ? trim($row[1]) : '';
                $answer = isset($row[2]) ? trim($row[2]) : '';
                
                $row[0] = $question;
                $row[1] = $answer;
                $row[2] = $subSubCategory;
            }
            unset($row);
        }

        // Dynamically parse and resolve Job Category and Sub Category columns for each row
        foreach ($rows as $key => &$row) {
            if ($key === 0 || empty($row[0])) continue;

            $catId = is_array($request->category_id) ? $request->category_id[count($request->category_id) - 1] : $request->category_id;

            if ($isAcademy) {
                $chapterName = isset($row[0]) ? trim($row[0]) : '';

                // Resolve Chapter category under the selected Class Category ($catId)
                $chapterId = null;
                if (!empty($chapterName) && $catId) {
                    $chapterCategory = \App\Models\Category::where('parent_id', $catId)
                        ->where('name', 'LIKE', '%' . $chapterName . '%')
                        ->first();
                    if (!$chapterCategory) {
                        $slug = preg_replace('/\s+/u', '-', trim($chapterName));
                        $slug = preg_replace('/[^\p{L}\p{N}-]/u', '', $slug);
                        $slug = mb_strtolower($slug, 'UTF-8');
                        $slug = preg_replace('/-+/', '-', $slug);
                        $slug = trim($slug, '-');
                        $slug = $slug ?: date('YmdHis') . '-' . rand(100, 999);
                        
                        if (\App\Models\Category::withTrashed()->where('slug', $slug)->exists()) {
                            $slug = $slug . '-' . rand(1000, 9999);
                        }

                        $chapterCategory = \App\Models\Category::create([
                            'uuid' => (string) \Illuminate\Support\Str::uuid(),
                            'parent_id' => $catId,
                            'admin_id' => auth()->guard('admin')->id(),
                            'name' => $chapterName,
                            'slug' => $slug,
                            'header' => $chapterName,
                            'content' => $chapterName,
                            'site_title' => $chapterName . ' - ' . get_settings('system_name'),
                            'meta_title' => $chapterName . ' - ' . get_settings('system_name'),
                            'status' => 1
                        ]);
                    }
                    $chapterId = $chapterCategory->id;
                }

                $subjectJobCategoryId = $request->job_category_id;

                // Shift row elements based on question type
                if ($request->question_type == 'mcq') {
                    $question = isset($row[1]) ? trim($row[1]) : '';
                    $opt1 = isset($row[2]) ? trim($row[2]) : '';
                    $opt2 = isset($row[3]) ? trim($row[3]) : '';
                    $opt3 = isset($row[4]) ? trim($row[4]) : '';
                    $opt4 = isset($row[5]) ? trim($row[5]) : '';
                    $opt5 = isset($row[6]) ? trim($row[6]) : '';
                    $correctAnswer = isset($row[7]) ? trim($row[7]) : '';
                    $hardLevel = isset($row[8]) ? trim($row[8]) : '';
                    $mark = isset($row[9]) ? trim($row[9]) : '';

                    $row[0] = $question;
                    $row[1] = $opt1;
                    $row[2] = $opt2;
                    $row[3] = $opt3;
                    $row[4] = $opt4;
                    $row[5] = $opt5;
                    $row[6] = $correctAnswer;
                    $row[7] = $hardLevel;
                    $row[8] = $mark;
                    $row[11] = $subjectJobCategoryId;
                    $row[12] = $chapterId;
                } elseif ($request->question_type == 'cq') {
                    $stimulus = isset($row[1]) ? trim($row[1]) : '';
                    $q_a = isset($row[2]) ? trim($row[2]) : '';
                    $ans_a = isset($row[3]) ? trim($row[3]) : '';
                    $q_b = isset($row[4]) ? trim($row[4]) : '';
                    $ans_b = isset($row[5]) ? trim($row[5]) : '';
                    $q_c = isset($row[6]) ? trim($row[6]) : '';
                    $ans_c = isset($row[7]) ? trim($row[7]) : '';
                    $q_d = isset($row[8]) ? trim($row[8]) : '';
                    $ans_d = isset($row[9]) ? trim($row[9]) : '';

                    $row[0] = $stimulus;
                    $row[1] = $q_a;
                    $row[2] = $ans_a;
                    $row[3] = $q_b;
                    $row[4] = $ans_b;
                    $row[5] = $q_c;
                    $row[6] = $ans_c;
                    $row[7] = $q_d;
                    $row[8] = $ans_d;
                    $row[11] = $subjectJobCategoryId;
                    $row[12] = $chapterId;
                } else {
                    $question = isset($row[1]) ? trim($row[1]) : '';
                    $correctAnswer = isset($row[2]) ? trim($row[2]) : '';
                    $hardLevel = isset($row[3]) ? trim($row[3]) : '';
                    $mark = isset($row[4]) ? trim($row[4]) : '';

                    $row[0] = $question;
                    $row[1] = $correctAnswer;
                    $row[2] = $hardLevel;
                    $row[3] = $mark;
                    $row[6] = $subjectJobCategoryId;
                    $row[7] = $chapterId;
                }
                
                continue;
            }

            if ($request->question_type == 'mcq') {
                if ($isCurrentAffairs) {
                    $jobCategoryName = isset($row[7]) ? trim($row[7]) : '';
                    $jobCategoryIndex = 8;
                    $subCategoryName = '';
                    $subCategoryIndex = null;
                } else {
                    $jobCategoryName = isset($row[9]) ? trim($row[9]) : '';
                    $subCategoryName = isset($row[10]) ? trim($row[10]) : '';
                    $jobCategoryIndex = 11; // We store resolved job_category_id at index 11
                    $subCategoryIndex = 12; // We store resolved sub_category_id at index 12
                }
            } elseif ($request->question_type == 'cq') {
                $jobCategoryName = isset($row[9]) ? trim($row[9]) : '';
                $subCategoryName = isset($row[10]) ? trim($row[10]) : '';
                $jobCategoryIndex = 11; // We store resolved job_category_id at index 11
                $subCategoryIndex = 12; // We store resolved sub_category_id at index 12
            } else {
                if ($isCurrentAffairs) {
                    $jobCategoryName = isset($row[2]) ? trim($row[2]) : '';
                    $jobCategoryIndex = 3;
                    $subCategoryName = '';
                    $subCategoryIndex = null;
                } else {
                    $jobCategoryName = isset($row[4]) ? trim($row[4]) : '';
                    $subCategoryName = isset($row[5]) ? trim($row[5]) : '';
                    $jobCategoryIndex = 6;
                    $subCategoryIndex = 7;
                }
            }
            
            $jobCategoryId = null;
            if (!empty($jobCategoryName)) {
                $jobCategory = \App\Models\JobCategory::where('category_id', $catId)
                    ->where('name', 'LIKE', '%' . $jobCategoryName . '%')
                    ->first();
                if (!$jobCategory) {
                    $jobCategory = \App\Models\JobCategory::create([
                        'admin_id' => auth()->guard('admin')->id(),
                        'category_id' => $catId,
                        'uuid' => (string) \Illuminate\Support\Str::uuid(),
                        'name' => $jobCategoryName,
                        'slug' => \Illuminate\Support\Str::slug($jobCategoryName) ?: (string) \Illuminate\Support\Str::uuid(),
                        'status' => 1
                    ]);
                }
                $jobCategoryId = $jobCategory->id;
            }

            $subCategoryId = null;
            if (!empty($subCategoryName)) {
                $subCategory = \App\Models\Category::where('parent_id', $catId)
                    ->where('name', 'LIKE', '%' . $subCategoryName . '%')
                    ->first();
                if (!$subCategory) {
                    // Unicode safe slug generation for Bengali category name
                    $slug = preg_replace('/\s+/u', '-', trim($subCategoryName));
                    $slug = preg_replace('/[^\p{L}\p{N}-]/u', '', $slug);
                    $slug = mb_strtolower($slug, 'UTF-8');
                    $slug = preg_replace('/-+/', '-', $slug);
                    $slug = trim($slug, '-');
                    $slug = $slug ?: date('YmdHis') . '-' . rand(100, 999);
                    
                    if (\App\Models\Category::withTrashed()->where('slug', $slug)->exists()) {
                        $slug = $slug . '-' . rand(1000, 9999);
                    }

                    $subCategory = \App\Models\Category::create([
                        'uuid' => (string) \Illuminate\Support\Str::uuid(),
                        'parent_id' => $catId,
                        'admin_id' => auth()->guard('admin')->id(),
                        'name' => $subCategoryName,
                        'slug' => $slug,
                        'header' => $subCategoryName,
                        'content' => $subCategoryName,
                        'site_title' => $subCategoryName . ' - ' . get_settings('system_name'),
                        'meta_title' => $subCategoryName . ' - ' . get_settings('system_name'),
                        'status' => 1
                    ]);
                }
                $subCategoryId = $subCategory->id;
            }

            $row[$jobCategoryIndex] = $jobCategoryId;
            if ($subCategoryIndex !== null) {
                $row[$subCategoryIndex] = $subCategoryId;
            }
        }
        unset($row);

        $questions = 0;
        foreach ($rows as $key => $row) {
            if ($key === 0 || empty($row[0])) continue;
            $questions++;
        }

        $isMath = $request->input('is_math', 0);
        $content = '';

        if($request->question_type == 'mcq') {
            $content = view('portal.question.card', compact('rows', 'isMath', 'isCurrentAffairs', 'isAcademy'))->render();
        } elseif($request->question_type == 'cq') {
            $content = view('portal.question.cq-card', compact('rows', 'isMath', 'isCurrentAffairs', 'isAcademy'))->render();
        } else {
            $content = view('portal.question.short-card', compact('rows', 'isMath', 'isCurrentAffairs', 'isAcademy'))->render();
        }

        // foreach ($rows as $key => $row) {
        //     if ($key == 0) {
        //         continue;
        //     }

        //     if($row[0] == '') {
        //         continue;
        //     }

        //     $counter++;
        //     $questions++;
            
        //     $correctAnswer = $row[6];
        //     $content .= '<div class="card mt-3">
        //             <div class="card-header">
        //                 <h4 class="card-title">Question '. $counter  .'</h4>
        //             </div>
        //             <div class="card-body row">
        //                 <div class="col-md-9 mb-3 form-group">
        //                     <label for="question_'. $counter  .'">Question <span class="text-danger">*</span></label>
        //                     <input type="text" name="questions['. $counter  .'][question]" id="question_'. $counter  .'" class="form-control" required value="'. $row[0] .'">
        //                     <input type="hidden" name="description[]" value="'. htmlspecialchars($row[0], ENT_QUOTES, 'UTF-8')  .'">
        //                 </div>

        //                 <div class="col-md-3 form-group mb-3">
        //                     <label for="correct_answer_'. $counter  .'">Correct Answer</label>
        //                     <select name="questions['. $counter  .'][correct_answer]" id="correct_answer_'. $counter  .'" class="form-control custom-select" required data-minimum-results-for-search="Infinity" data-placeholder="Select One">
        //                         <option value="">Select One</option>
        //                         <option '. ($correctAnswer == 1 ? 'selected' : '') .' value="1">Option One</option>
        //                         <option '. ($correctAnswer == 2 ? 'selected' : '') .' value="2">Option Two</option>
        //                         <option '. ($correctAnswer == 3 ? 'selected' : '') .' value="3">Option Three</option>
        //                         <option '. ($correctAnswer == 4 ? 'selected' : '') .' value="4">Option Four</option>
        //                         <option '. ($correctAnswer == 5 ? 'selected' : '') .' value="4">Option Five</option>
        //                     </select>
        //                 </div>

        //                 <div class="col-md-12 row" id="correct_answer_area">
        //                     <div class="col-md-6 form-group mb-3">
        //                         <label for="option_one_'. $counter  .'">Option One <span class="text-danger">*</span></label>
        //                         <input type="text" name="questions['. $counter  .'][option_one]" id="option_one_'. $counter  .'" class="form-control" required value="'. htmlspecialchars($row[1]) .'">
        //                     </div>

        //                     <div class="col-md-6 form-group mb-3">
        //                         <label for="option_two_'. $counter  .'">Option Two <span class="text-danger">*</span></label>
        //                         <input type="text" name="questions['. $counter  .'][option_two]" id="option_two_'. $counter  .'" class="form-control" required value="'. htmlspecialchars($row[2]) .'">
        //                     </div>

        //                     <div class="col-md-6 form-group mb-3">
        //                         <label for="option_three_'. $counter  .'">Option Three </label>
        //                         <input type="text" name="questions['. $counter  .'][option_three]" id="option_three_'. $counter  .'" class="form-control" value="'. htmlspecialchars($row[3]) .'">
        //                     </div>

        //                     <div class="col-md-6 form-group mb-3">
        //                         <label for="option_four_'. $counter  .'">Option Four</label>
        //                         <input type="text" name="questions['. $counter  .'][option_four]" id="option_four_'. $counter  .'" class="form-control" value="'. htmlspecialchars($row[4]) .'">
        //                     </div>
                            
        //                     <div class="col-md-6 form-group mb-3">
        //                         <label for="option_five_'. $counter  .'">Option Five</label>
        //                         <input type="text" name="questions['. $counter  .'][option_five]" id="option_five_'. $counter  .'" class="form-control" value="'. htmlspecialchars($row[5]) .'">
        //                     </div>
        //                 </div>
        //             </div>
        //         </div>';
        // }

        return response()->json([
            'status' => true,
            'html' => $content,
            'message' => ($questions) . ' Question Found',
        ]);
    }

    public function store($request)
    {
        return $this->questionRepository->store($request);
    }

    public function update($id, $request)
    {
        return $this->questionRepository->update($request, $id);
    }

    public function destroy($id)
    {
        return $this->questionRepository->delete($id);
    }

    public function restore($uuid)
    {
        $model = $this->questionRepository->getDeletedItemByUUID($uuid);
        if (!$model) {
            return response()->json([
                'status' => false,
                'message' => 'Question not found or already active'
            ]);
        }

        $action = $this->questionRepository->restoreDeletedItemByUUID($model);
        if(!$action) {
            return response()->json([
                'status' => false,
                'message' => 'Something Went Wrong while Restoring.'
            ]);
        }

        return response()->json([
            'load' => true,
            'status' => true,
            'message' => 'Question restored successfully.'
        ]);
    }
    
    public function forceDelete($uuid)
    {
        $model = $this->questionRepository->getDeletedItemByUUID($uuid);
        if (!$model) {
            return response()->json([
                'status' => false,
                'message' => 'Question not found or already deleted.'
            ]);
        }

        $action = $this->questionRepository->forceDeleteItemByUUID($model);
        if(!$action) {
            return response()->json([
                'status' => false,
                'message' => 'Something went wrong while force deleting.'
            ]);
        }

        return response()->json([
            'load' => true,
            'status' => true,
            'message' => 'Question deleted successfully.'
        ]);
    }
}