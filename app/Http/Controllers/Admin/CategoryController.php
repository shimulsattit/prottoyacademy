<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Services\CategoryService;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    protected $categoryService;

    public function __construct(CategoryService $categoryService)
    {
        $this->categoryService = $categoryService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if (auth()->guard('admin')->user()->hasPermissionTo('category.view') === false) {
            return redirect()->route('portal.dashboard')->with('error', 'You don\'t have permission.');
        }

        if ($request->ajax()) {
            return $this->categoryService->dataTable();
        }

        return view('portal.category.index');
    }

    public function trashedItem(Request $request)
    {
        if (auth()->guard('admin')->user()->hasPermissionTo('recycle_bin.view') === false) {
            return redirect()->route('portal.dashboard')->with('error', 'You don\'t have permission.');
        }

        if ($request->ajax()) {
            return $this->categoryService->binDataTable();
        }

        return view('portal.category.bin');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        if (auth()->guard('admin')->user()->hasPermissionTo('category.create') === false) {
            return redirect()->route('portal.dashboard')->with('error', 'You don\'t have permission.');
        }

        $parent_id = null;
        if($request->parent_id) {
            $parent_id = $request->parent_id;
        }

        return view('portal.category.create', compact('parent_id'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if (auth()->guard('admin')->user()->hasPermissionTo('category.create') === false) {
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
        if (auth()->guard('admin')->user()->hasPermissionTo('category.update') === false) {
            return redirect()->route('portal.dashboard')->with('error', 'You don\'t have permission.');
        }

        $model = $this->categoryService->find($id);
        if(!$model) {
            return redirect()->route('portal.category.index');
        }
        return view('portal.category.edit', compact('model'));
        
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        if (auth()->guard('admin')->user()->hasPermissionTo('category.update') === false) {
            return redirect()->route('portal.dashboard')->with('error', 'You don\'t have permission.');
        }

        return $this->categoryService->update($id, $request);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        if (auth()->guard('admin')->user()->hasPermissionTo('category.delete') === false) {
            return redirect()->route('portal.dashboard')->with('error', 'You don\'t have permission.');
        }

        return $this->categoryService->destroy($id);
    }

    public function restore(string $id)
    {
        return $this->categoryService->restore($id);
    }

    public function forceDelete(string $id)
    {
        return $this->categoryService->forceDelete($id);
    }

    public function getCategories(Request $request)
    {
        $parentId = $request->input('parent_id'); // Parent ID
        
        $categories = Category::where('parent_id', $parentId)->get(); 
        
        $treeData = $categories->map(function ($category) {
            return [
                'id' => $category->id,
                'parent' => $category->parent_id ? $category->parent_id : "#",
                'text' => $category->name,
                'children' => true 
            ];
        });

        return response()->json($treeData);
    }
    
    public function getSearchedCategories(Request $request)
    {
        $categories = Category::where('name', 'LIKE', '%'. $request->str .'%')->get(); 
        
        $treeData = $categories->map(function ($category) {
            return [
                'id' => $category->id,
                'parent' => $category->parent_id ? $category->parent_id : "#",
                'text' => $category->name,
                'children' => true 
            ];
        });

        return response()->json($treeData);
    }
}
