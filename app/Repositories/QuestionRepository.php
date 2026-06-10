<?php

namespace App\Repositories;

use App\Models\Option;
use App\Models\Question;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Repositories\Interface\QuestionRepositoryInterface;

class QuestionRepository implements QuestionRepositoryInterface
{
    public function all()
    {
        return Question::with(['category', 'admin'])->select('questions.*');
    }

    public function onlyTrashed()
    {
        return Question::onlyTrashed()->get();
    }

    public function store($request)
    {
        $validator = Validator::make($request->all(), [
            'category_id'       => 'required',
            'job_category_id'   => 'nullable|integer|exists:job_categories,id',
            'year_id'           => 'nullable|integer|exists:years,id',
            'exam_id'           => 'nullable|integer|exists:exams,id',
            'passage_id'        => 'nullable|integer|exists:passages,id',
            'question_type'     => 'required|string|in:mcq,cq,short_answer,long_answer,true_false, fill_in_the_blanks,matching',
            // 'question'          => 'required|string',
            // 'slug'              => 'required|string|unique:questions,slug',
            // 'correct_answer'    => 'required|string',
            // 'hard_level'        => 'required|string|in:easy,medium,hard',
            // 'question_mark'     => 'required|string',
            // 'description'       => 'required|string',
            // 'site_title'        => 'required|string',
            // 'meta_title'        => 'required|string',
            // 'meta_keywords'     => 'nullable|string',
            // 'meta_description'  => 'nullable|string',
            // 'meta_article_tag'  => 'nullable|string',
            // 'status'            => 'required|boolean',

            // 'option_one'        => 'sometimes|required_if:question_type,mcq|nullable',
            // 'option_two'        => 'sometimes|required_if:question_type,mcq|nullable',
            // 'option_three'        => 'sometimes|required_if:question_type,mcq|nullable',
            // 'option_four'        => 'sometimes|required_if:question_type,mcq|nullable',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'validator' => true,
                'message' => $validator->errors()
            ]);
        }

        // dd($request->all());

        $passageId = $request->passage_id;
        if ($request->question_type === 'cq' && $request->has('stimulus')) {
            $passageText = $request->stimulus;
            if ($request->hasFile('stimulus_image')) {
                $file = $request->file('stimulus_image');
                $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                
                // Ensure directory exists
                if (!file_exists(public_path('uploads/passages'))) {
                    mkdir(public_path('uploads/passages'), 0777, true);
                }
                
                $file->move(public_path('uploads/passages'), $filename);
                $imageUrl = asset('uploads/passages/' . $filename);
                $passageText .= '<br><img src="' . $imageUrl . '" class="img-fluid" style="max-width: 100%; height: auto;">';
            }
            
            $passage = new \App\Models\Passage();
            $passage->admin_id = auth()->guard('admin')->user()->id;
            $passage->uuid = (string) \Illuminate\Support\Str::uuid();
            $passage->name = \Illuminate\Support\Str::limit(strip_tags($request->stimulus), 50);
            $passage->passage = $passageText;
            $passage->status = 1;
            $passage->save();
            
            $passageId = $passage->id;
        }

        DB::transaction(function () use ($request, $passageId) {
            $questions = $request->questions;
            if($questions && is_array($questions) && count($questions) > 0) {
                // Check if this is an imported CQ format: each element has 'stimulus' and 'sub_questions'
                $isImportedCQ = ($request->question_type === 'cq' && isset(reset($questions)['stimulus']));

                if ($isImportedCQ) {
                    foreach ($questions as $cqKey => $cqData) {
                        $stimulusText = $cqData['stimulus'] ?? '';
                        if (empty($stimulusText)) {
                            continue;
                        }
                        
                        // Create a Passage for this specific CQ row
                        $passage = new \App\Models\Passage();
                        $passage->admin_id = auth()->guard('admin')->user()->id;
                        $passage->uuid = (string) \Illuminate\Support\Str::uuid();
                        $passage->name = \Illuminate\Support\Str::limit(strip_tags($stimulusText), 50);
                        $passage->passage = $stimulusText;
                        $passage->status = 1;
                        $passage->save();
                        
                        $rowPassageId = $passage->id;
                        $subQuestions = $cqData['sub_questions'] ?? [];
                        
                        foreach ($subQuestions as $subIndex => $subQ) {
                            $qText = $subQ['question'] ?? '';
                            if (empty($qText)) {
                                continue;
                            }
                            
                            if ($request->is_math == 1) {
                                $slug = date('Y-m-d-h-i-s-'). rand(1000000, 9999999);
                            } else {
                                $slug = \Illuminate\Support\Str::slug($qText) ?: date('d-m-Y-h-i-s');
                                $slug = substr($slug, 0, 150);
                                if (Question::withTrashed()->where('slug', $slug)->exists()) {
                                    $slug = $slug . '-'. \Illuminate\Support\Str::random(5). '-' . date('d-m-Y-h-i-s');
                                }
                            }
                            
                            $model = new Question();
                            $model->admin_id = auth()->guard('admin')->user()->id;
                            $model->category_id = $request->category_id[count($request->category_id) - 1];
                            $model->job_category_id = isset($cqData['job_category_id']) && $cqData['job_category_id'] !== '' ? $cqData['job_category_id'] : $request->job_category_id;
                            $model->year_id = $request->year_id;
                            $model->exam_id = $request->exam_id;
                            $model->passage_id = $rowPassageId;
                            $model->uuid = (string) Str::uuid();
                            $model->question_type = $request->question_type;
                            $model->question = $qText;
                            $model->correct_answer = $subQ['correct_answer'] ?? '';
                            $model->hard_level = $request->hard_level ?? 'easy';
                            $model->question_mark = $subQ['question_mark'] ?? 1;
                            $model->slug = $slug;
                            $model->content = null;
                            $model->site_title = \Illuminate\Support\Str::limit(strip_tags($qText), 60) . ' - '. get_settings('system_name');
                            $model->meta_title = \Illuminate\Support\Str::limit(strip_tags($qText), 60) . ' - '. get_settings('system_name');
                            $model->meta_keywords = $request->meta_keywords ?? null;
                            $model->meta_description = \Illuminate\Support\Str::limit(strip_tags($qText), 150) . ' - '. get_settings('system_name');
                            $model->status = $request->status;
                            $model->save();
                        }
                    }
                } else {
                    foreach($questions as $questionKey => $question) {
                        if($request->is_math == 1) {
                            $slug = date('Y-m-d-h-i-s-'). rand(1000000, 9999999);
                        } else {
                            $qText = $question['question'] ?? '';
                            $slug = \Illuminate\Support\Str::slug($qText) ?: date('d-m-Y-h-i-s');
                            $slug = substr($slug, 0, 150);
                            if (Question::withTrashed()->where('slug', $slug)->exists()) {
                                $slug = $slug . '-'. \Illuminate\Support\Str::random(5). '-' . date('d-m-Y-h-i-s');
                            }
                        }

                        $model = new Question();
                        $model->admin_id = auth()->guard('admin')->user()->id;
                        $model->category_id = $request->category_id[count($request->category_id) - 1];
                        $model->job_category_id = isset($question['job_category_id']) && $question['job_category_id'] !== '' ? $question['job_category_id'] : $request->job_category_id;
                        $model->year_id = $request->year_id;
                        $model->exam_id = $request->exam_id;
                        $model->passage_id = $passageId ?? $request->passage_id;
                        $model->uuid = (string) Str::uuid();
                        $model->question_type = $request->question_type;
                        $model->question = $question['question'];
                        $model->correct_answer = $question['correct_answer'];
                        $model->hard_level = $request->hard_level ?? 'easy';
                        $model->question_mark = $question['question_mark'] ?? $request->question_mark ?? 1;
                        $model->slug = $slug;
                        $model->content = $question['description'] ?? null;
                        $model->site_title = $question['site_title'] ?? \Illuminate\Support\Str::limit(strip_tags($question['question']), 60) . ' - '. get_settings('system_name') ;
                        $model->meta_title = $question['meta_title'] ?? \Illuminate\Support\Str::limit(strip_tags($question['question']), 60) . ' - '. get_settings('system_name') ;
                        $model->meta_keywords = $request->meta_keywords ?? $question['meta_keywords'] ?? null;
                        $model->meta_description = $question['meta_description'] ?? \Illuminate\Support\Str::limit(strip_tags($question['question']), 150) . ' - '. get_settings('system_name');
                        $model->meta_article_tag = $question['meta_article_tag'] ?? null;
                        $model->status = $request->status;
                        $model->save();

                        if(isset($question['description']) && $question['description'] && $model) {
                            $log = new \App\Models\QuestionDescriptionLog();
                            $log->type = 'question';
                            $log->question_id = $model->id;
                            $log->admin_id = auth()->guard('admin')->user()->id;
                            $log->description = $question['description'];
                            $log->save();
                        }
                
                        if($model && $model->question_type == 'mcq') {
                            $option = new Option();
                            $option->question_id = $model->id;
                            $option->option_one = $question['option_one'];
                            $option->option_two = $question['option_two'] ?? null;
                            $option->option_three = $question['option_three'] ?? null;
                            $option->option_four = $question['option_four'] ?? null;
                            $option->option_five = $question['option_five'] ?? null;
                            $option->save();
                        }
                    }
                }
            }
        });
        
        return response()->json([
            'load' => true,
            'status' => true,
            'message' => 'Question Created Successfully',
        ]);
    }

    public function getById($id)
    {
        return Question::find($id);
    }
    
    public function getByUUId($uuid)
    {
        return Question::where('uuid', $uuid)->first();
    }

    public function update($request, $id)
    {
        $model = Question::where('uuid', $id)->first();
        if(!$model) {
            return response()->json([
                'status' => false, 
                'message' => 'Exam Not Found'
            ]);
        }

        $validator = Validator::make($request->all(), [
            'category_id'       => 'required',
            'job_category_id'   => 'nullable|integer|exists:job_categories,id',
            'year_id'           => 'nullable|integer|exists:years,id',
            'exam_id'           => 'nullable|integer|exists:exams,id',
            'passage_id'        => 'nullable|integer|exists:passages,id',
            'question_type'     => 'required|string|in:mcq,cq,short_answer,long_answer,true_false, fill_in_the_blanks,matching',
            // 'question'          => 'required|string',
            // 'slug'              => 'required|string|unique:questions,slug,'. $model->id,
            // 'correct_answer'    => 'required|string',
            // 'hard_level'        => 'required|string|in:easy,medium,hard',
            // 'question_mark'     => 'required|string',
            // 'description'       => 'required|string',
            // 'site_title'        => 'required|string',
            // 'meta_title'        => 'required|string',
            // 'meta_keywords'     => 'nullable|string',
            // 'meta_description'  => 'nullable|string',
            // 'meta_article_tag'  => 'nullable|string',
            // 'status'            => 'required|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'validator' => true,
                'message' => $validator->errors()
            ]);
        }

        $model->category_id = is_array($request->category_id) ? $request->category_id[count($request->category_id) - 1] : $request->category_id;
        $model->job_category_id = $request->job_category_id;
        $model->year_id = $request->year_id;
        $model->exam_id = $request->exam_id;
        $model->passage_id = $request->passage_id;
        $model->question_type = $request->question_type;
        $model->question = $request->question;
        $model->correct_answer = $request->correct_answer;
        $model->hard_level = $request->hard_level ?? $model->hard_level ?? 'easy';
        $model->question_mark = $request->question_mark ?? $model->question_mark ?? 1;
        $model->content = $request->description;
        $model->site_title = $request->site_title;
        $model->meta_title = $request->meta_title;
        $model->meta_keywords = $request->meta_keywords;
        $model->meta_description = $request->meta_description;
        $model->meta_article_tag = $request->meta_article_tag;
        $model->status = $request->status;
        $model->save();

        if($request->description != '' && $model) {
            $log = new \App\Models\QuestionDescriptionLog();
            $log->type = 'question';
            $log->question_id = $model->id;
            $log->admin_id = auth()->guard('admin')->user()->id;
            $log->description = $request->description;
            $log->save();
        }

        if($model && $model->question_type == 'mcq') {
            $option = Option::where('question_id', $model->id)->first();
            $option->option_one = $request->option_one;
            $option->option_two = $request->option_two;
            $option->option_three = $request->option_three;
            $option->option_four = $request->option_four;
            $option->option_five = $request->option_five;
            $option->save();
        }

        if($request->method && $request->method == 'short') {
            return response()->json([
                'status' => true,
                'message' => 'Question Updated Successfully',
                'question_id' => $model->id,
                'question' => $model->question,
                'correct_answer' => $model->correct_answer,
                'content' => $model->content,
                'option_one' => isset($option) ? $option->option_one : '',
                'option_two' => isset($option) ? $option->option_two : '',
                'option_three' => isset($option) ? $option->option_three : '',
                'option_four' => isset($option) ? $option->option_four : ''
            ]);
        } else {
            return response()->json([
                'status' => true,
                'message' => 'Question Updated Successfully',
                'goto' => route('portal.question.edit', $model->uuid)
            ]);
        }
    }

    public function delete($id)
    {
        $model = Question::where('uuid', $id)->first();
        if(!$model) {
            return response()->json([
                'status' => false, 
                'message' => 'Question Not Found'
            ]);
        }

        $model->delete();
        
        return response()->json([
            'load' => true,
            'status' => true, 
            'message' => 'Question deleted successfully'
        ]);
    }

    public function getDeletedItemByUUID($uuid)
    {
        return Question::onlyTrashed()->where('uuid', $uuid)->first();
    }

    public function restoreDeletedItemByUUID($model)
    {
        return $model->restore();
    }

    public function forceDeleteItemByUUID($model)
    {
        return $model->forceDelete();
    }
}
