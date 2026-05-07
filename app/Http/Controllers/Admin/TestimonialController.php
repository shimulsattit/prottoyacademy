<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Images;
use App\Models\Testimonial;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;

class TestimonialController extends Controller
{
    public function all()
    {
        return Testimonial::all();
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
                
                ->editColumn('star', function ($model) {
                    return $model->star . ' out of 5';
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
                    return view('portal.testimonial.action', compact('model'));
                })
                ->editColumn('created_at', function($model) {
                    return date('d F, Y h:i A', strtotime($model->created_at));
                })
                ->rawColumns(['action', 'star', 'category', 'url', 'status', 'created_at'])
                ->make(true);
        }

        return view('portal.testimonial.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        return view('portal.testimonial.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'              => 'required|string|max:250',
            'designation'       => 'required|string|max:250',
            'picture'           => 'nullable',
            'star'              => 'required|string|in:1,2,3,4,5',
            'content'           => 'nullable|string',
            'status'            => 'required|boolean',
            'show_on_home_page' => 'required|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'validator' => true,
                'message' => $validator->errors()
            ]);
        }

        $model = new Testimonial();
        
        $model->name = $request->name;
        $model->designation = $request->designation;
        $model->content = $request->content;
        $model->star = $request->star;
        $model->status = $request->status;
        $model->show_on_home_page = $request->show_on_home_page;

        if ($request->hasFile('picture')) {
            $model->picture = Images::upload('testimonial', $request->picture);
        }

        $model->save();

        return response()->json([
            'status' => true,
            'message' => 'Record Created Successfully',
            'goto' => route('portal.testimonial.index')
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
        return Testimonial::find($id);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $model = $this->find($id);
        if(!$model) {
            return redirect()->route('portal.testimonial.index');
        }

        return view('portal.testimonial.edit', compact('model'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $model = Testimonial::findOrFail($id);
        if(!$model) {
            return response()->json([
                'status' => false, 
                'message' => 'Record Not Found'
            ]);
        }

        $validator = Validator::make($request->all(), [
            'name'              => 'required|string|max:250',
            'designation'       => 'required|string|max:250',
            'picture'           => 'nullable',
            'star'              => 'required|string|in:1,2,3,4,5',
            'content'           => 'nullable|string',
            'status'            => 'required|boolean',
            'show_on_home_page' => 'required|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'validator' => true,
                'message' => $validator->errors()
            ]);
        }

        $model->name = $request->name;
        $model->designation = $request->designation;
        $model->content = $request->content;
        $model->star = $request->star;
        $model->status = $request->status;
        $model->show_on_home_page = $request->show_on_home_page;

        if ($request->hasFile('picture')) {
            $model->picture = Images::upload('testimonial', $request->picture);
        }

        $model->save();

        return response()->json([
            'status' => true,
            'message' => 'Record Updated Successfully',
            'goto' => route('portal.testimonial.index')
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $model = Testimonial::findOrFail($id);
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
