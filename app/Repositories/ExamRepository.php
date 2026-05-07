<?php

namespace App\Repositories;

use App\Models\Exam;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;
use App\Repositories\Interface\ExamRepositoryInterface;

class ExamRepository implements ExamRepositoryInterface
{
    public function all()
    {
        return Exam::all();
    }

    public function onlyTrashed()
    {
        return Exam::onlyTrashed()->get();
    }

    public function store($request)
    {
        $validator = Validator::make($request->all(), [
            'job_category_id'   => 'required|integer|exists:job_categories,id',
            'year_id'           => 'required|integer|exists:years,id',
            'name'              => 'required|string|min:4',
            'slug'              => 'required|string|unique:years,slug',
            'name_in_bangla'    => 'nullable|string',
            'description'       => 'required|string',
            'site_title'        => 'required|string',
            'meta_title'        => 'required|string',
            'meta_keywords'     => 'nullable|string',
            'meta_description'  => 'nullable|string',
            'meta_article_tag'  => 'nullable|string',
            'status'            => 'required|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'validator' => true,
                'message' => $validator->errors()
            ]);
        }

        $model = new Exam();
        $model->job_category_id = $request->job_category_id;
        $model->year_id = $request->year_id;
        $model->admin_id = auth()->guard('admin')->user()->id;
        $model->uuid = (string) Str::uuid();
        $model->name = $request->name;
        $model->slug = $request->slug;
        $model->name_in_bangla = $request->name_in_bangla;
        $model->content = $request->description;
        $model->site_title = $request->site_title;
        $model->meta_title = $request->meta_title;
        $model->meta_keywords = $request->meta_keywords;
        $model->meta_description = $request->meta_description;
        $model->meta_article_tag = $request->meta_article_tag;
        $model->status = $request->status;
        $model->save();

        return response()->json([
            'status' => true,
            'message' => 'Exam Created Successfully',
            'goto' => route('portal.exam.index')
        ]);
    }

    public function getById($id)
    {
        return Exam::find($id);
    }
    
    public function getByUUId($uuid)
    {
        return Exam::where('uuid', $uuid)->first();
    }

    public function update($request, $id)
    {
        $model = Exam::where('uuid', $id)->first();
        if(!$model) {
            return response()->json([
                'status' => false, 
                'message' => 'Exam Not Found'
            ]);
        }

        $validator = Validator::make($request->all(), [
            'job_category_id'   => 'required|integer|exists:job_categories,id',
            'year_id'           => 'required|integer|exists:years,id',
            'name'              => 'required|string|min:4',
            'slug'              => 'required|string|unique:years,slug,'. $model->id,
            'name_in_bangla'    => 'nullable|string',
            'description'       => 'required|string',
            'site_title'        => 'required|string',
            'meta_title'        => 'required|string',
            'meta_keywords'     => 'nullable|string',
            'meta_description'  => 'nullable|string',
            'meta_article_tag'  => 'nullable|string',
            'status'            => 'required|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'validator' => true,
                'message' => $validator->errors()
            ]);
        }

        $model->job_category_id = $request->job_category_id;
        $model->year_id = $request->year_id;
        $model->name = $request->name;
        $model->slug = $request->slug;
        $model->name_in_bangla = $request->name_in_bangla;
        $model->content = $request->description;
        $model->site_title = $request->site_title;
        $model->meta_title = $request->meta_title;
        $model->meta_keywords = $request->meta_keywords;
        $model->meta_description = $request->meta_description;
        $model->meta_article_tag = $request->meta_article_tag;
        $model->status = $request->status;
        $model->save();

        return response()->json([
            'status' => true,
            'message' => 'Exam Updated Successfully',
            'goto' => route('portal.exam.index')
        ]);
    }

    public function delete($id)
    {
        $model = Exam::where('uuid', $id)->first();
        if(!$model) {
            return response()->json([
                'status' => false, 
                'message' => 'Exam Not Found'
            ]);
        }

        $model->delete();
        
        return response()->json([
            'load' => true,
            'status' => true, 
            'message' => 'Exam deleted successfully'
        ]);
    }

    public function getDeletedItemByUUID($uuid)
    {
        return Exam::onlyTrashed()->where('uuid', $uuid)->first();
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
