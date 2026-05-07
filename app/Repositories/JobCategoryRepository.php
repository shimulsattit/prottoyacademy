<?php

namespace App\Repositories;

use App\Models\JobCategory;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;
use App\Repositories\Interface\JobCategoryRepositoryInterface;

class JobCategoryRepository implements JobCategoryRepositoryInterface
{
    public function all()
    {
        return JobCategory::all();
    }

    public function onlyTrashed()
    {
        return JobCategory::onlyTrashed()->get();
    }

    public function store($request)
    {
        $validator = Validator::make($request->all(), [
            'name'              => 'required|string|min:4',
            'category_id'       => 'required|integer|exists:categories,id',
            'slug'              => 'required|string|unique:job_categories,slug',
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

        $model = new JobCategory();
        $model->admin_id = auth()->guard('admin')->user()->id;
        $model->uuid = (string) Str::uuid();
        $model->category_id = $request->category_id;
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
            'message' => 'Job Category Created Successfully',
            'goto' => route('portal.job-category.index')
        ]);
    }

    public function getById($id)
    {
        return JobCategory::find($id);
    }
    
    public function getByUUId($uuid)
    {
        return JobCategory::where('uuid', $uuid)->first();
    }

    public function update($request, $id)
    {
        $model = JobCategory::where('uuid', $id)->first();
        if(!$model) {
            return response()->json([
                'status' => false, 
                'message' => 'Category Not Found'
            ]);
        }

        $validator = Validator::make($request->all(), [
            'name'              => 'required|string|min:4',
            'category_id'       => 'required|integer|exists:categories,id',
            'slug'              => 'required|string|unique:job_categories,slug,'. $model->id,
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

        $model->category_id = $request->category_id;
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
            'message' => 'Job Category Updated Successfully',
            'goto' => route('portal.job-category.index')
        ]);
    }

    public function delete($id)
    {
        $model = JobCategory::where('uuid', $id)->first();
        if(!$model) {
            return response()->json([
                'status' => false, 
                'message' => 'Job Category Not Found'
            ]);
        }

        $model->delete();
        
        return response()->json([
            'load' => true,
            'status' => true, 
            'message' => 'Job Category deleted successfully'
        ]);
    }

    public function getDeletedItemByUUID($uuid)
    {
        return JobCategory::onlyTrashed()->where('uuid', $uuid)->first();
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
