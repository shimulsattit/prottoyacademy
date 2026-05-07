<?php

namespace App\Repositories;

use App\Models\Category;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;
use App\Repositories\Interface\CategoryRepositoryInterface;

class CategoryRepository implements CategoryRepositoryInterface
{
    public function all()
    {
        return Category::all();
    }

    public function getAllCategoryIds($categoryId)
    {
        $categoryIds = [$categoryId];

        $childCategories = Category::where('parent_id', $categoryId)->get();

        foreach ($childCategories as $child) {
            $categoryIds = array_merge($categoryIds, $this->getAllCategoryIds($child->id));
        }

        return $categoryIds;
    }


    public function onlyTrashed()
    {
        return Category::onlyTrashed()->get();
    }

    public function store($request)
    {
        $validator = Validator::make($request->all(), [
            'parent_id'         => 'nullable|integer',
            'name'              => 'required|string|min:4',
            'slug'              => 'required|string|unique:categories,slug',
            'name_in_bangla'    => 'nullable|string',
            'header'            => 'required|string',
            'header_in_bangla'  => 'nullable|string',
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

        $model = new Category();
        $model->parent_id = $request->parent_id;
        $model->admin_id = auth()->guard('admin')->user()->id;
        $model->uuid = (string) Str::uuid();
        $model->name = $request->name;
        $model->slug = $request->slug;
        $model->name_in_bangla = $request->name_in_bangla;
        $model->header = $request->header;
        $model->header_in_bangla = $request->header_in_bangla;
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
            'message' => 'Category Created Successfully',
            'goto' => route('portal.category.index')
        ]);
    }

    public function getById($id)
    {
        return Category::find($id);
    }

    public function update($request, $id)
    {
        $model = Category::where('uuid', $id)->first();
        if(!$model) {
            return response()->json([
                'status' => false, 
                'message' => 'Category Not Found'
            ]);
        }

        $validator = Validator::make($request->all(), [
            'parent_id'         => 'nullable|integer',
            'name'              => 'required|string|min:4',
            'slug'              => 'required|string|unique:categories,slug,'. $model->id,
            'name_in_bangla'    => 'nullable|string',
            'header'            => 'required|string',
            'header_in_bangla'  => 'nullable|string',
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

        $model->parent_id = $request->parent_id;
        $model->name = $request->name;
        $model->slug = $request->slug;
        $model->name_in_bangla = $request->name_in_bangla;
        $model->header = $request->header;
        $model->header_in_bangla = $request->header_in_bangla;
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
            'message' => 'Category Updated Successfully',
            'goto' => route('portal.category.index')
        ]);
    }

    public function delete($id)
    {
        $model = Category::where('uuid', $id)->first();
        if(!$model) {
            return response()->json([
                'status' => false, 
                'message' => 'Category Not Found'
            ]);
        }

        $model->delete();
        
        return response()->json([
            'load' => true,
            'status' => true, 
            'message' => 'Category deleted successfully'
        ]);
    }

    public function getDeletedItemByUUID($uuid)
    {
        return Category::onlyTrashed()->where('uuid', $uuid)->first();
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
