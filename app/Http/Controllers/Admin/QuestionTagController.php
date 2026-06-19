<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\QuestionTag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class QuestionTagController extends Controller
{
    public function all()
    {
        return QuestionTag::all();
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $models = $this->all();
            return DataTables::of($models)
                ->addIndexColumn()
                ->editColumn('status', function ($model) {
                    return $model->status ? '<span class="badge badge-success">Active</span>' : '<span class="badge badge-warning">Inactive</span>';
                })
                ->addColumn('action', function ($model) {
                    return view('portal.question-tag.action', compact('model'));
                })
                ->editColumn('created_at', function ($model) {
                    return date('d F, Y h:i A', strtotime($model->created_at));
                })
                ->rawColumns(['action', 'status'])
                ->make(true);
        }

        return view('portal.question-tag.index');
    }

    public function create()
    {
        return view('portal.question-tag.create');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:question_tags,slug',
            'status' => 'required|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'validator' => true,
                'message' => $validator->errors()
            ]);
        }

        $data = $request->all();
        if (empty($data['slug'])) {
            $data['slug'] = \Illuminate\Support\Str::slug($data['name']);
            if (QuestionTag::where('slug', $data['slug'])->exists()) {
                $data['slug'] .= '-' . time();
            }
        }

        $model = QuestionTag::create($data);

        return response()->json([
            'status' => true,
            'message' => 'Tag Created Successfully',
            'goto' => route('portal.question-tag.index')
        ]);
    }

    public function edit($id)
    {
        $model = QuestionTag::find($id);
        if (!$model) return redirect()->route('portal.question-tag.index');
        return view('portal.question-tag.edit', compact('model'));
    }

    public function update(Request $request, $id)
    {
        $model = QuestionTag::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:question_tags,slug,' . $id,
            'status' => 'required|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'validator' => true,
                'message' => $validator->errors()
            ]);
        }

        $data = $request->all();
        if (empty($data['slug'])) {
            $data['slug'] = \Illuminate\Support\Str::slug($data['name']);
            if (QuestionTag::where('slug', $data['slug'])->where('id', '!=', $id)->exists()) {
                $data['slug'] .= '-' . time();
            }
        }

        $model->update($data);

        return response()->json([
            'status' => true,
            'message' => 'Tag Updated Successfully',
            'goto' => route('portal.question-tag.index')
        ]);
    }

    public function destroy($id)
    {
        $model = QuestionTag::findOrFail($id);
        $model->delete();

        return response()->json([
            'status' => true,
            'load' => true,
            'message' => 'Tag Deleted Successfully'
        ]);
    }
}
