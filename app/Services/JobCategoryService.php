<?php 

namespace App\Services;

use Yajra\DataTables\Facades\DataTables;
use App\Repositories\Interface\JobCategoryRepositoryInterface;

class JobCategoryService {

    protected $jobCategoryRepository;

    public function __construct(JobCategoryRepositoryInterface $jobCategoryRepository)
    {
        $this->jobCategoryRepository = $jobCategoryRepository;
    }
    
    public function all()
    {
        return $this->jobCategoryRepository->all();
    }

    public function dataTable()
    {
        $models = $this->all();
        return DataTables::of($models)
            ->addIndexColumn()
            ->editColumn('status', function ($model) {
                if($model->status == 1) {
                    $status = '<span class="badge badge-success">Publish</span>';
                } else {
                    $status = '<span class="badge badge-warning">Unpublish</span>';
                }

                return $status;
            })
            ->editColumn('category', function($model) {
                return $model->category ? $model->category->name : 'N/A';
            })
            ->editColumn('created_at', function($model) {
                return date('d F, Y h:i A', strtotime($model->created_at));
            })
            ->addColumn('action', function ($model) {
                return view('portal.job-category.action', compact('model'));
            })
            ->rawColumns(['action', 'status', 'category', 'created_at'])
            ->make(true);
    }

    public function binDataTable()
    {
        $models = $this->jobCategoryRepository->onlyTrashed();
        return DataTables::of($models)
            ->addIndexColumn()
            ->editColumn('status', function ($model) {
                if($model->status == 1) {
                    $status = '<span class="badge badge-success">Publish</span>';
                } else {
                    $status = '<span class="badge badge-warning">Unpublish</span>';
                }

                return $status;
            })
            ->editColumn('created_at', function($model) {
                return date('d F, Y h:i A', strtotime($model->created_at));
            })
            ->editColumn('deleted_at', function($model) {
                return date('d F, Y h:i A', strtotime($model->deleted_at));
            })
            ->addColumn('action', function ($model) {
                return view('portal.job-category.bin-action', compact('model'));
            })
            ->rawColumns(['action', 'status', 'created_at', 'deleted_at'])
            ->make(true);
    }

    public function find($id)
    {
        return $this->jobCategoryRepository->getByUUId($id);
    }

    public function store($request)
    {
        return $this->jobCategoryRepository->store($request);
    }

    public function update($id, $request)
    {
        return $this->jobCategoryRepository->update($request, $id);
    }

    public function destroy($id)
    {
        return $this->jobCategoryRepository->delete($id);
    }

    public function restore($uuid)
    {
        $model = $this->jobCategoryRepository->getDeletedItemByUUID($uuid);
        if (!$model) {
            return response()->json([
                'status' => false,
                'message' => 'Job Category not found or already active'
            ]);
        }

        $action = $this->jobCategoryRepository->restoreDeletedItemByUUID($model);
        if(!$action) {
            return response()->json([
                'status' => false,
                'message' => 'Something Went Wrong while Restoring.'
            ]);
        }

        return response()->json([
            'load' => true,
            'status' => true,
            'message' => 'Job Category restored successfully.'
        ]);
    }
    
    public function forceDelete($uuid)
    {
        $model = $this->jobCategoryRepository->getDeletedItemByUUID($uuid);
        if (!$model) {
            return response()->json([
                'status' => false,
                'message' => 'Job Category not found or already deleted.'
            ]);
        }

        $action = $this->jobCategoryRepository->forceDeleteItemByUUID($model);
        if(!$action) {
            return response()->json([
                'status' => false,
                'message' => 'Something went wrong while force deleting.'
            ]);
        }

        return response()->json([
            'load' => true,
            'status' => true,
            'message' => 'Job Category deleted successfully.'
        ]);
    }

}