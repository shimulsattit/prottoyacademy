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
            'question_type'     => 'required|string|in:mcq,short_answer,long_answer,true_false, fill_in_the_blanks,matching',
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

        // Dynamically parse and resolve Job Category column for each row
        foreach ($rows as $key => &$row) {
            if ($key === 0 || empty($row[0])) continue;

            $jobCategoryName = isset($row[$request->question_type == 'mcq' ? 7 : 2]) ? trim($row[$request->question_type == 'mcq' ? 7 : 2]) : '';
            $jobCategoryId = null;

            if (!empty($jobCategoryName)) {
                $catId = is_array($request->category_id) ? $request->category_id[count($request->category_id) - 1] : $request->category_id;
                $jobCategory = \App\Models\JobCategory::where('category_id', $catId)
                    ->where('name', 'LIKE', '%' . $jobCategoryName . '%')
                    ->first();
                if (!$jobCategory) {
                    $jobCategory = \App\Models\JobCategory::create([
                        'admin_id' => auth()->guard('admin')->id(),
                        'category_id' => $catId,
                        'uuid' => (string) \Illuminate\Support\Str::uuid(),
                        'name' => $jobCategoryName,
                        'slug' => \Illuminate\Support\Str::slug($jobCategoryName),
                        'status' => 1
                    ]);
                }
                $jobCategoryId = $jobCategory->id;
            }
            // Append the resolved ID to the row array at specific index
            $row[$request->question_type == 'mcq' ? 8 : 3] = $jobCategoryId;
        }
        unset($row);

        $questions = 0;
        $content = '';

        $counter = 0;

        if($request->question_type == 'mcq') {
            $content = view('portal.question.card', compact('rows'))->render();
        } else {
            $content = view('portal.question.short-card', compact('rows'))->render();
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