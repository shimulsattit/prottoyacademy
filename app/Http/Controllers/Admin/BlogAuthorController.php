<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BlogAuthor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;
use App\Http\Images;

class BlogAuthorController extends Controller
{
    public function all()
    {
        return BlogAuthor::all();
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
                    return view('portal.blog-author.action', compact('model'));
                })
                ->editColumn('created_at', function($model){
                    return date('d F, Y h:i A', strtotime($model->created_at));
                })
                ->rawColumns(['action','status'])
                ->make(true);
        }

        return view('portal.blog-author.index');
    }

    public function create()
    {
        return view('portal.blog-author.create');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'              => 'required|string|max:255',
            'slug'              => 'nullable|string|max:255|unique:blog_authors,slug',
            'email'             => 'nullable|email|max:255',
            'designation'       => 'nullable|string|max:255',
            'content'           => 'nullable|string',
            'profile_picture'   => 'nullable|image',
            'bio'               => 'nullable|string|max:500',
            'meta_title'        => 'nullable|string|max:255',
            'meta_keyword'      => 'nullable|string|max:255',
            'meta_description'  => 'nullable|string|max:500',
            'status'            => 'required|boolean',
        ]);

        if($validator->fails()){
            return response()->json([
                'status'=>false,
                'validator'=>true,
                'message'=>$validator->errors()
            ]);
        }

        $model = new BlogAuthor();
        $model->fill($request->all());

        if($request->hasFile('profile_picture')){
            $model->profile_picture = Images::upload('blog-author',$request->profile_picture);
        }

        $model->save();

        return response()->json([
            'status'=>true,
            'message'=>'Author Created Successfully',
            'goto'=>route('portal.blog-author.index')
        ]);
    }

    public function edit($id)
    {
        $model = BlogAuthor::find($id);
        if(!$model) return redirect()->route('portal.blog-author.index');
        return view('portal.blog-author.edit', compact('model'));
    }

    public function update(Request $request, $id)
    {
        $model = BlogAuthor::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'name'              => 'required|string|max:255',
            'slug'              => 'nullable|string|max:255|unique:blog_authors,slug,'.$id,
            'email'             => 'nullable|email|max:255',
            'designation'       => 'nullable|string|max:255',
            'content'           => 'nullable|string',
            'profile_picture'   => 'nullable|image',
            'bio'               => 'nullable|string|max:500',
            'meta_title'        => 'nullable|string|max:255',
            'meta_keyword'      => 'nullable|string|max:255',
            'meta_description'  => 'nullable|string|max:500',
            'status'            => 'required|boolean',
        ]);

        if($validator->fails()){
            return response()->json([
                'status'=>false,
                'validator'=>true,
                'message'=>$validator->errors()
            ]);
        }

        $model->fill($request->all());

        if($request->hasFile('profile_picture')){
            $model->profile_picture = Images::upload('blog-author',$request->profile_picture);
        }

        $model->save();

        return response()->json([
            'status'=>true,
            'message'=>'Author Updated Successfully',
            'goto'=>route('portal.blog-author.index')
        ]);
    }

    public function destroy($id)
    {
        $model = BlogAuthor::findOrFail($id);
        $model->delete();

        return response()->json([
            'status'=>true,
            'load'=>true,
            'message'=>'Author Deleted Successfully'
        ]);
    }
}
