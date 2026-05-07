<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BlogCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class BlogCategoryController extends Controller
{
    public function all()
    {
        return BlogCategory::all();
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $models = $this->all();
            return DataTables::of($models)
                ->addIndexColumn()
                ->editColumn('status', function($model){
                    return $model->status ? '<span class="badge badge-success">Publish</span>' : '<span class="badge badge-warning">Unpublish</span>';
                })
                ->addColumn('action', function($model){
                    return view('portal.blog-category.action', compact('model'));
                })
                ->editColumn('created_at', function($model){
                    return date('d F, Y h:i A', strtotime($model->created_at));
                })
                ->rawColumns(['action','status'])
                ->make(true);
        }

        return view('portal.blog-category.index');
    }

    public function create()
    {
        return view('portal.blog-category.create');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'               => 'required|string|max:255',
            'slug'               => 'required|string|max:255|unique:blog_categories,slug',
            'status'             => 'required|boolean',
            'content'            => 'nullable|string',
            'site_title'         => 'nullable|string|max:255',
            'meta_title'         => 'nullable|string|max:255',
            'meta_keyword'       => 'nullable|string|max:255',
            'meta_description'   => 'nullable|string|max:500',
            'meta_google_schema' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'=>false,
                'validator'=>true,
                'message'=>$validator->errors()
            ]);
        }

        $model = BlogCategory::create($request->all());

        return response()->json([
            'status'=>true,
            'message'=>'Category Created Successfully',
            'goto'=>route('portal.blog-category.index')
        ]);
    }

    public function edit($id)
    {
        $model = BlogCategory::find($id);
        if (!$model) return redirect()->route('portal.blog-category.index');
        return view('portal.blog-category.edit', compact('model'));
    }

    public function update(Request $request, $id)
    {
        $model = BlogCategory::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'name'               => 'required|string|max:255',
            'slug'               => 'nullable|string|max:255|unique:blog_categories,slug,'.$id,
            'status'             => 'required|boolean',
            'content'            => 'nullable|string',
            'site_title'         => 'nullable|string|max:255',
            'meta_title'         => 'nullable|string|max:255',
            'meta_keyword'       => 'nullable|string|max:255',
            'meta_description'   => 'nullable|string|max:500',
            'meta_google_schema' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'=>false,
                'validator'=>true,
                'message'=>$validator->errors()
            ]);
        }

        $model->update($request->all());

        return response()->json([
            'status'=>true,
            'message'=>'Category Updated Successfully',
            'goto'=>route('portal.blog-category.index')
        ]);
    }

    public function destroy($id)
    {
        $model = BlogCategory::findOrFail($id);
        $model->delete();

        return response()->json([
            'status'=>true,
            'load'=>true,
            'message'=>'Category Deleted Successfully'
        ]);
    }
}
