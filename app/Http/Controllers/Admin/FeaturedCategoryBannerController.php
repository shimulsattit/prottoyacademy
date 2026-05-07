<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Images;
use App\Models\FeaturedCategory;
use App\Models\FeaturedCategoryBanner;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;

class FeaturedCategoryBannerController extends Controller
{
    public function all()
    {
        return FeaturedCategoryBanner::all();
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $models = $this->all();
            return DataTables::of($models)
                ->addIndexColumn()
                ->editColumn('banner', function($model) {
                    return Images::show($model->banner);
                })
                ->editColumn('category', function ($model) {
                    return $model->category ? $model->category->category->name : 'N/A';
                })
                ->editColumn('status', function ($model) {
                    if($model->status == 1) {
                        $status = '<span class="badge badge-success">Publish</span>';
                    } else {
                        $status = '<span class="badge badge-warning">Unpublish</span>';
                    }

                    return $status;
                })
                ->addColumn('action', function ($model) {
                    return view('portal.featured-banner.action', compact('model'));
                })
                ->editColumn('created_at', function($model) {
                    return date('d F, Y h:i A', strtotime($model->created_at));
                })
                ->rawColumns(['action', 'banner', 'category', 'url', 'status', 'created_at'])
                ->make(true);
        }

        return view('portal.featured-banner.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $categories = FeaturedCategory::where('status', 1)->get();
        return view('portal.featured-banner.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'category_id' => 'required|integer|exists:featured_categories,id',
            'banner' => 'required',
            'alt_tag' => 'required|string|max:250',
            'content' => 'nullable|string|max:250',
            'status'      => 'required|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'validator' => true,
                'message' => $validator->errors()
            ]);
        }

        $model = new FeaturedCategoryBanner();
        
        $model->category_id = $request->category_id;
        $model->alt_tag = $request->alt_tag;
        $model->content = $request->content;
        $model->status = $request->status;

        if ($request->hasFile('banner')) {
            $model->banner = Images::upload('featured-categories-banner', $request->banner);
        }

        $model->save();

        return response()->json([
            'status' => true,
            'message' => 'Record Created Successfully',
            'goto' => route('portal.featured-banners.index')
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    public function find($id)
    {
        return FeaturedCategoryBanner::find($id);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $model = $this->find($id);
        if(!$model) {
            return redirect()->route('portal.featured-categories.index');
        }
        $categories = FeaturedCategory::where('status', 1)->get();
        return view('portal.featured-banner.edit', compact('model', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $model = FeaturedCategoryBanner::findOrFail($id);
        if(!$model) {
            return response()->json([
                'status' => false, 
                'message' => 'Record Not Found'
            ]);
        }

        $validator = Validator::make($request->all(), [
            'category_id' => 'required|integer|exists:featured_categories,id',
            'banner' => 'nullable',
            'alt_tag' => 'required|string|max:250',
            'content' => 'nullable|string|max:250',
            'status'      => 'required|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'validator' => true,
                'message' => $validator->errors()
            ]);
        }

        $model->category_id = $request->category_id;
        $model->alt_tag = $request->alt_tag;
        $model->content = $request->content;
        $model->status = $request->status;

        if ($request->hasFile('banner')) {
            $model->banner = Images::upload('featured-categories-banner', $request->banner);
        }

        $model->save();

        return response()->json([
            'status' => true,
            'message' => 'Record Updated Successfully',
            'goto' => route('portal.featured-banners.index')
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $model = FeaturedCategoryBanner::findOrFail($id);
        if(!$model) {
            return response()->json([
                'status' => false, 
                'message' => 'Record Not Found'
            ]);
        }

        $model->delete();
        
        return response()->json([
            'load' => true,
            'status' => true, 
            'message' => 'Record deleted successfully'
        ]);
    }
}
