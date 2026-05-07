<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use App\Models\BlogCategory;
use App\Models\BlogAuthor;
use App\Models\BlogTag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;
use App\Http\Images;

class BlogController extends Controller
{
    public function all()
    {
        return Blog::with(['category','author','tags'])->get();
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
                ->addColumn('picture', function($model){
                    return Images::show($model->thumbnail_image);
                })
                ->addColumn('category', function($model){
                    return $model->category ? $model->category->name : '-';
                })
                ->addColumn('author', function($model){
                    return $model->author ? $model->author->name : '-';
                })
                ->addColumn('tags', function($model){
                    return $model->tags->pluck('name')->join(', ');
                })
                ->addColumn('action', function ($model) {
                    return view('portal.blog.action', compact('model'));
                })
                ->editColumn('created_at', function($model) {
                    return date('d F, Y h:i A', strtotime($model->created_at));
                })
                ->rawColumns(['action', 'picture', 'status'])
                ->make(true);
        }

        return view('portal.blog.index');
    }

    public function create()
    {
        $categories = BlogCategory::where('status',1)->get();
        $authors = BlogAuthor::where('status',1)->get();
        $tags = BlogTag::where('status',1)->get();
        return view('portal.blog.create', compact('categories','authors','tags'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'blog_category_id' => 'required|exists:blog_categories,id',
            'blog_author_id'   => 'required|exists:blog_authors,id',
            'title'            => 'required|string|max:255',
            'slug'             => 'nullable|string|max:255|unique:blogs,slug',
            'short_description'=> 'nullable|string|max:500',
            'content'          => 'nullable|string',
            'thumbnail_image'  => 'nullable|image',
            'banner_image'     => 'nullable|image',
            'featured'         => 'required|boolean',
            'status'           => 'required|boolean',
        ]);

        if($validator->fails()) {
            return response()->json([
                'status'=>false,
                'validator'=>true,
                'message'=>$validator->errors()
            ]);
        }

        $model = new Blog();
        $model->fill($request->all());
        $model->content = $request->content;

        if($request->hasFile('thumbnail_image')){
            $model->thumbnail_image = Images::upload('blogs',$request->thumbnail_image);
        }
        if($request->hasFile('banner_image')){
            $model->banner_image = Images::upload('blogs',$request->banner_image);
        }

        $model->save();

        if($request->tags){
            $model->tags()->sync($request->tags);
        }

        return response()->json([
            'status'=>true,
            'message'=>'Blog Created Successfully',
            'goto'=>route('portal.blog.index')
        ]);
    }

    public function edit($id)
    {
        $model = Blog::with('tags')->find($id);
        if(!$model) return redirect()->route('portal.blog.index');

        $categories = BlogCategory::where('status',1)->get();
        $authors = BlogAuthor::where('status',1)->get();
        $tags = BlogTag::where('status',1)->get();

        return view('portal.blog.edit', compact('model','categories','authors','tags'));
    }

    public function update(Request $request, $id)
    {
        $model = Blog::findOrFail($id);
        $validator = Validator::make($request->all(), [
            'blog_category_id' => 'required|exists:blog_categories,id',
            'blog_author_id'   => 'required|exists:blog_authors,id',
            'title'            => 'required|string|max:255',
            'slug'             => 'nullable|string|max:255|unique:blogs,slug,'.$id,
            'short_description'=> 'nullable|string|max:500',
            'content'          => 'nullable|string',
            'thumbnail_image'  => 'nullable|image',
            'banner_image'     => 'nullable|image',
            'featured'         => 'required|boolean',
            'status'           => 'required|boolean',
            'published_at'     => 'nullable|date',
        ]);

        if($validator->fails()){
            return response()->json([
                'status'=>false,
                'validator'=>true,
                'message'=>$validator->errors()
            ]);
        }

        $model->fill($request->all());
        $model->content = $request->content;

        if($request->hasFile('thumbnail_image')){
            $model->thumbnail_image = Images::upload('blogs',$request->thumbnail_image);
        }
        if($request->hasFile('banner_image')){
            $model->banner_image = Images::upload('blogs',$request->banner_image);
        }

        $model->save();

        if($request->tags){
            $model->tags()->sync($request->tags);
        }

        return response()->json([
            'status'=>true,
            'message'=>'Blog Updated Successfully',
            'goto'=>route('portal.blog.index')
        ]);
    }

    public function destroy($id)
    {
        $model = Blog::findOrFail($id);
        $model->delete();

        return response()->json([
            'status'=>true,
            'load'=>true,
            'message'=>'Blog Deleted Successfully'
        ]);
    }
}
