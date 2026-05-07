<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Images;
use Illuminate\Http\Request;
use App\Models\Page;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Validator;

class PageController extends Controller
{
    public function getAllPages()
    {
        return Page::all();
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $models = $this->getAllpages();
            return DataTables::of($models)
                ->addIndexColumn()
                ->editColumn('parent', function($model) {
                    return $model->parent_id != null ? $model->parent->title : 'Parent Page';
                })
                ->editColumn('url', function ($model) {
                    return '<a style="color: #000;" href="'. URL::to($model->slug) .'" target="_blank"> Visit '. $model->title .' Page</a>';
                })
                ->editColumn('status', function ($model) {
                    $checked = $model->status == 1 ? 'checked' : '';
                    return '<div class="form-check form-switch"><input data-url="' . route('portal.page.status', $model->id) . '" class="form-check-input" type="checkbox" role="switch" name="status" id="status' . $model->id . '" ' . $checked . ' data-id="' . $model->id . '"></div>';
                })
                ->addColumn('action', function ($model) {
                    return view('portal.page.action', compact('model'));
                })
                ->rawColumns(['action', 'parent', 'status', 'url'])
                ->make(true);
        }

        return view('portal.page.index');
    }

    public function create()
    {
        return view('portal.page.create');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'          => 'required|string|max:255',
            'meta_image'    => 'nullable|image|max:2048',
            'status'        => 'required',
            'slug'          => 'required|string|max:255|unique:pages,slug',
            'description'   => 'required|string',
            'meta_title'    => 'required|string|max:255',
            'meta_keyword'  => 'required|string',
            'meta_description' => 'required|string',
            'meta_article_tag' => 'nullable|string',
            'meta_script_tag'   => 'nullable|string'
        ]);

        if($validator->fails()) {
            return response()->json([
                'status'=>false,
                'validator'=>true,
                'message'=>$validator->errors()
            ]);
        }
        
        Page::create([
            'title' => $request->name,
            'slug' => $request->slug,
            'status' => $request->status,
            'content' => $request->description,
            'meta_title' => $request->meta_title,
            'meta_keyword' => $request->meta_keyword,
            'meta_description' => $request->meta_description,
            'meta_article_tag' => $request->meta_article_tag,
            'meta_script_tag' => $request->meta_script_tag,
            'meta_image' => $request->meta_image ? Images::upload('pages', $request->meta_image) : null,
        ]);

        $json = ['status' => true, 'goto' => route('portal.page.index'), 'message' => 'Page created successfully'];
        return response()->json($json);
    }

    public function edit($id)
    {
        $model = Page::findOrFail($id);
        return view('portal.page.edit', compact('model'));
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name'          => 'required|string|max:255',
            'meta_image'    => 'nullable|image|max:2048',
            'status'        => 'required',
            'slug'          => 'required|string|max:255|unique:pages,slug,', $id,
            'description'   => 'required|string',
            'meta_title'    => 'required|string|max:255',
            'meta_keyword'  => 'required|string',
            'meta_description' => 'required|string',
            'meta_article_tag' => 'nullable|string',
            'meta_script_tag'   => 'nullable|string'
        ]);

        if($validator->fails()) {
            return response()->json([
                'status'=>false,
                'validator'=>true,
                'message'=>$validator->errors()
            ]);
        }

        $page = Page::findOrFail($id);
        $page->title = $request->name;
        $page->slug = $request->slug;
        $page->status = $request->status;
        $page->content = $request->description;
        $page->meta_title = $request->meta_title;
        $page->meta_keyword = $request->meta_keyword;
        $page->meta_description = $request->meta_description;
        $page->meta_article_tag = $request->meta_article_tag;
        $page->meta_script_tag = $request->meta_script_tag;

        if($request->meta_image) {
            $page->meta_image = Images::upload('pages', $request->meta_image);
        }

        $page->update();

        return response()->json([
            'status' => true, 
            'goto' => route('portal.page.index'), 
            'message' => 'Page updated successfully.'
        ]);
    }

    public function destroy($id)
    {
        $page = Page::findOrFail($id);
        $page->delete();

        return response()->json([
            'status' => true, 
            'load' => true,
            'message' => "Page deleted successfully"
        ]);
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|boolean',
        ]);

        $page = Page::find($id);

        if (!$page) {
            return response()->json(['success' => false, 'message' => 'Page not found.'], 404);
        }

        $page->status = $request->input('status');
        $page->save();

        return response()->json([
            'success' => true, 
            'message' => 'Page status updated successfully.' 
        ]);
    }
}
