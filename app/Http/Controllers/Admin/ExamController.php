<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Exam;
use App\Services\ExamService;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class ExamController extends Controller
{
    protected $examService;

    public function __construct(ExamService $examService)
    {
        $this->examService = $examService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if (auth()->guard('admin')->user()->hasPermissionTo('exam.view') === false) {
            return redirect()->route('portal.dashboard')->with('error', 'You don\'t have permission.');
        }

        if ($request->ajax()) {
            return $this->examService->dataTable();
        }

        return view('portal.exam.index');
    }

    /**
     * Display a listing of the deleted resource.
     */
    public function trashedItem(Request $request)
    {
        if (auth()->guard('admin')->user()->hasPermissionTo('recycle_bin.view') === false) {
            return redirect()->route('portal.dashboard')->with('error', 'You don\'t have permission.');
        }

        if ($request->ajax()) {
            return $this->examService->binDataTable();
        }

        return view('portal.exam.bin');
    }

    public function getExamByYear(Request $request) 
    {
        $exams = Exam::select('id', 'name')->where('status', 1)->where('year_id', $request->year_id)->get();
    
        return response()->json([
            'status' => true,
            'exams' => $exams
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        if (auth()->guard('admin')->user()->hasPermissionTo('exam.create') === false) {
            return redirect()->route('portal.dashboard')->with('error', 'You don\'t have permission.');
        }

        return view('portal.exam.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if (auth()->guard('admin')->user()->hasPermissionTo('exam.store') === false) {
            return redirect()->route('portal.dashboard')->with('error', 'You don\'t have permission.');
        }

        return $this->examService->store($request);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        if (auth()->guard('admin')->user()->hasPermissionTo('exam.update') === false) {
            return redirect()->route('portal.dashboard')->with('error', 'You don\'t have permission.');
        }

        $model = $this->examService->find($id);
        if(!$model) {
            return redirect()->route('portal.exam.index');
        }
        return view('portal.exam.edit', compact('model'));
        
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        if (auth()->guard('admin')->user()->hasPermissionTo('exam.update') === false) {
            return redirect()->route('portal.dashboard')->with('error', 'You don\'t have permission.');
        }

        return $this->examService->update($id, $request);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        if (auth()->guard('admin')->user()->hasPermissionTo('exam.delete') === false) {
            return redirect()->route('portal.dashboard')->with('error', 'You don\'t have permission.');
        }

        return $this->examService->destroy($id);
    }
    
    /**
     * Restore the specified resource from storage.
     */
    public function restore(string $id)
    {
        return $this->examService->restore($id);
    }

    /**
     * Force Delete the specified resource from storage.
     */
    public function forceDelete(string $id)
    {
        return $this->examService->forceDelete($id);
    }
}
