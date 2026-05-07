<?php 

namespace App\Services;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use App\Repositories\Interface\CategoryRepositoryInterface;

class CategoryService {
    protected $categoryRepository;

    public function __construct(CategoryRepositoryInterface $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
    }

    public function all()
    {
        return $this->categoryRepository->all();
    }

    public function getAllCategoryIds($categoryId)
    {
        return $this->categoryRepository->getAllCategoryIds($categoryId);
    }

    public function onlyTrashed()
    {
        return $this->categoryRepository->onlyTrashed();
    }

    public function dataTable()
    {
        $models = $this->all();
        return DataTables::of($models)
            ->addIndexColumn()
            ->addColumn('action', function ($model) {
                return view('portal.category.action', compact('model'));
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function binDataTable()
    {
        $models = $this->onlyTrashed();
        return DataTables::of($models)
            ->addIndexColumn()
            ->editColumn('created_at', function($model) {
                return date('d F, Y h:i A', strtotime($model->created_at));
            })
            ->editColumn('deleted_at', function($model) {
                return date('d F, Y h:i A', strtotime($model->deleted_at));
            })
            ->addColumn('action', function ($model) {
                return view('portal.category.action', compact('model'));
            })
            ->rawColumns(['action', 'created_at', 'deleted_at'])
            ->make(true);
    }

    public function find($id)
    {
        return $this->categoryRepository->getById($id);
    }

    public function store($request)
    {
        return $this->categoryRepository->store($request);
    }

    public function update($id, $request)
    {
        return $this->categoryRepository->update($request, $id);
    }

    public function destroy($id)
    {
        return $this->categoryRepository->delete($id);
    }

    public function restore($uuid)
    {
        $model = $this->categoryRepository->getDeletedItemByUUID($uuid);
        if (!$model) {
            return response()->json([
                'status' => false,
                'message' => 'Category not found or already active'
            ]);
        }

        $action = $this->categoryRepository->restoreDeletedItemByUUID($model);
        if(!$action) {
            return response()->json([
                'status' => false,
                'message' => 'Something Went Wrong while Restoring.'
            ]);
        }

        return response()->json([
            'load' => true,
            'status' => true,
            'message' => 'Category restored successfully.'
        ]);
    }
    
    public function forceDelete($uuid)
    {
        $model = $this->categoryRepository->getDeletedItemByUUID($uuid);
        if (!$model) {
            return response()->json([
                'status' => false,
                'message' => 'Category not found or already deleted.'
            ]);
        }

        $action = $this->categoryRepository->forceDeleteItemByUUID($model);
        if(!$action) {
            return response()->json([
                'status' => false,
                'message' => 'Something went wrong while force deleting.'
            ]);
        }

        return response()->json([
            'load' => true,
            'status' => true,
            'message' => 'Category deleted successfully.'
        ]);
    }

}