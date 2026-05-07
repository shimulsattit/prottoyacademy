<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\PassageService;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class PassageController extends Controller
{
    protected $passageService;

    public function __construct(PassageService $passageService)
    {
        $this->passageService = $passageService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if (auth()->guard('admin')->user()->hasPermissionTo('passage.view') === false) {
            return redirect()->route('portal.dashboard')->with('error', 'You don\'t have permission.');
        }

        if ($request->ajax()) {
            return $this->passageService->dataTable();
        }

        return view('portal.passage.index');
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
            return $this->passageService->binDataTable();
        }

        return view('portal.passage.bin');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        if (auth()->guard('admin')->user()->hasPermissionTo('passage.create') === false) {
            return redirect()->route('portal.dashboard')->with('error', 'You don\'t have permission.');
        }

        return view('portal.passage.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if (auth()->guard('admin')->user()->hasPermissionTo('passage.create') === false) {
            return redirect()->route('portal.dashboard')->with('error', 'You don\'t have permission.');
        }

        return $this->passageService->store($request);
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
        if (auth()->guard('admin')->user()->hasPermissionTo('passage.update') === false) {
            return redirect()->route('portal.dashboard')->with('error', 'You don\'t have permission.');
        }

        $model = $this->passageService->find($id);
        if(!$model) {
            return redirect()->route('portal.passage.index');
        }
        return view('portal.passage.edit', compact('model'));
        
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        if (auth()->guard('admin')->user()->hasPermissionTo('passage.update') === false) {
            return redirect()->route('portal.dashboard')->with('error', 'You don\'t have permission.');
        }

        return $this->passageService->update($id, $request);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        if (auth()->guard('admin')->user()->hasPermissionTo('passage.delete') === false) {
            return redirect()->route('portal.dashboard')->with('error', 'You don\'t have permission.');
        }

        return $this->passageService->destroy($id);
    }

    /**
     * Restore the specified resource from storage.
     */
    public function restore(string $id)
    {
        return $this->passageService->restore($id);
    }

    /**
     * Force Delete the specified resource from storage.
     */
    public function forceDelete(string $id)
    {
        return $this->passageService->forceDelete($id);
    }
}