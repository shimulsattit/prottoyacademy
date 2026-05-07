<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BlogTag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class BlogTagController extends Controller
{
    public function all()
    {
        return BlogTag::all();
    }

    public function index(Request $request)
    {
        if($request->ajax()){
            $models = $this->all();
            return DataTables::of($models)
                ->addIndexColumn()
                ->editColumn('status', function($model){
                    return $model->status ? '<span class="badge badge-success">Active</span>' : '<span class="badge badge-warning">Inactive</span>';
                })
                ->addColumn('action', function($model){
                    return view('portal.blog-tag.action', compact('model'));
                })
                ->editColumn('created_at', function($model){
                    return date('d F, Y h:i A', strtotime($model->created_at));
                })
                ->rawColumns(['action','status'])
                ->make(true);
        }

        return view('portal.blog-tag.index');
    }

    public function create()
    {
        return view('portal.blog-tag.create');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:blog_tags,slug',
            'status' => 'required|boolean',
        ]);

        if($validator->fails()){
            return response()->json([
                'status'=>false,
                'validator'=>true,
                'message'=>$validator->errors()
            ]);
        }

        $model = BlogTag::create($request->all());

        return response()->json([
            'status'=>true,
            'message'=>'Tag Created Successfully',
            'goto'=>route('portal.blog-tag.index')
        ]);
    }

    public function edit($id)
    {
        $model = BlogTag::find($id);
        if(!$model) return redirect()->route('portal.blog-tag.index');
        return view('portal.blog-tag.edit', compact('model'));
    }

    public function update(Request $request, $id)
    {
        $model = BlogTag::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:blog_tags,slug,'.$id,
            'status' => 'required|boolean',
        ]);

        if($validator->fails()){
            return response()->json([
                'status'=>false,
                'validator'=>true,
                'message'=>$validator->errors()
            ]);
        }

        $model->update($request->all());

        return response()->json([
            'status'=>true,
            'message'=>'Tag Updated Successfully',
            'goto'=>route('portal.blog-tag.index')
        ]);
    }

    public function destroy($id)
    {
        $model = BlogTag::findOrFail($id);
        $model->delete();

        return response()->json([
            'status'=>true,
            'load'=>true,
            'message'=>'Tag Deleted Successfully'
        ]);
    }
}
