<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\JobCategoryService;
use Illuminate\Http\Request;

class JobCategoryController extends Controller
{
    protected $categoryService;

    public function __construct(JobCategoryService $categoryService)
    {
        $this->categoryService = $categoryService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if (auth()->guard('admin')->user()->hasPermissionTo('job_category.view') === false) {
            return redirect()->route('portal.dashboard')->with('error', 'You don\'t have permission.');
        }

        if ($request->ajax()) {
            return $this->categoryService->dataTable();
        }

        return view('portal.job-category.index');
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
            return $this->categoryService->binDataTable();
        }

        return view('portal.job-category.bin');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        if (auth()->guard('admin')->user()->hasPermissionTo('job_category.create') === false) {
            return redirect()->route('portal.dashboard')->with('error', 'You don\'t have permission.');
        }

        return view('portal.job-category.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if (auth()->guard('admin')->user()->hasPermissionTo('job_category.create') === false) {
            return redirect()->route('portal.dashboard')->with('error', 'You don\'t have permission.');
        }

        return $this->categoryService->store($request);
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
        if (auth()->guard('admin')->user()->hasPermissionTo('job_category.update') === false) {
            return redirect()->route('portal.dashboard')->with('error', 'You don\'t have permission.');
        }

        $model = $this->categoryService->find($id);
        if(!$model) {
            return redirect()->route('portal.job-category.index');
        }
        return view('portal.job-category.edit', compact('model'));
        
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        if (auth()->guard('admin')->user()->hasPermissionTo('job_category.update') === false) {
            return redirect()->route('portal.dashboard')->with('error', 'You don\'t have permission.');
        }

        return $this->categoryService->update($id, $request);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        if (auth()->guard('admin')->user()->hasPermissionTo('job_category.delete') === false) {
            return redirect()->route('portal.dashboard')->with('error', 'You don\'t have permission.');
        }

        return $this->categoryService->destroy($id);
    }

    /**
     * Restore the specified resource from storage.
     */
    public function restore(string $id)
    {
        return $this->categoryService->restore($id);
    }

    /**
     * Force Delete the specified resource from storage.
     */
    public function forceDelete(string $id)
    {
        return $this->categoryService->forceDelete($id);
    }
}
