<?php

namespace App\Repositories;

use App\Models\Year;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;
use App\Repositories\Interface\YearRepositoryInterface;

class YearRepository implements YearRepositoryInterface
{
    public function all()
    {
        return Year::all();
    }

    public function onlyTrashed()
    {
        return Year::onlyTrashed()->get();
    }

    public function store($request)
    {
        $validator = Validator::make($request->all(), [
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

        $model = new Year();
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
            'message' => 'Year Created Successfully',
            'goto' => route('portal.year.index')
        ]);
    }

    public function getById($id)
    {
        return Year::find($id);
    }
    
    public function getByUUId($uuid)
    {
        return Year::where('uuid', $uuid)->first();
    }

    public function update($request, $id)
    {
        $model = Year::where('uuid', $id)->first();
        if(!$model) {
            return response()->json([
                'status' => false, 
                'message' => 'Category Not Found'
            ]);
        }

        $validator = Validator::make($request->all(), [
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
            'message' => 'Year Updated Successfully',
            'goto' => route('portal.year.index')
        ]);
    }

    public function delete($id)
    {
        $model = Year::where('uuid', $id)->first();
        if(!$model) {
            return response()->json([
                'status' => false, 
                'message' => 'Year Not Found'
            ]);
        }

        $model->delete();
        
        return response()->json([
            'load' => true,
            'status' => true, 
            'message' => 'Year deleted successfully'
        ]);
    }

    public function getDeletedItemByUUID($uuid)
    {
        return Year::onlyTrashed()->where('uuid', $uuid)->first();
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
