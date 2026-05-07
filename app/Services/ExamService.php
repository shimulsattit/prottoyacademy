<?php 

namespace App\Services;

use Yajra\DataTables\Facades\DataTables;
use App\Repositories\Interface\ExamRepositoryInterface;

class ExamService {

    protected $examServiceRepository;

    public function __construct(ExamRepositoryInterface $examServiceRepository)
    {
        $this->examServiceRepository = $examServiceRepository;
    }

    public function all()
    {
        return $this->examServiceRepository->all();
    }

    public function dataTable()
    {
        $models = $this->all();
        return DataTables::of($models)
            ->addIndexColumn()
            ->addColumn('action', function ($model) {
                return view('portal.exam.action', compact('model'));
            })
            ->editColumn('status', function ($model) {
                if($model->status == 1) {
                    $status = '<span class="badge badge-success">Publish</span>';
                } else {
                    $status = '<span class="badge badge-warning">Unpublish</span>';
                }

                return $status;
            })
            ->editColumn('job_category', function($model) {
                return $model->job_category ? $model->job_category->name : '';
            })
            ->editColumn('year', function($model) {
                return $model->year ? $model->year->name : '';
            })
            ->editColumn('created_at', function($model) {
                return date('d F, Y h:i A', strtotime($model->created_at));
            })
            ->rawColumns(['action', 'status', 'created_at', 'job_category', 'year'])
            ->make(true);
    }

    public function binDataTable()
    {
        $models = $this->examServiceRepository->onlyTrashed();
        return DataTables::of($models)
            ->addIndexColumn()
            ->addColumn('action', function ($model) {
                return view('portal.exam.bin-action', compact('model'));
            })
            ->editColumn('status', function ($model) {
                if($model->status == 1) {
                    $status = '<span class="badge badge-success">Publish</span>';
                } else {
                    $status = '<span class="badge badge-warning">Unpublish</span>';
                }

                return $status;
            })
            ->editColumn('job_category', function($model) {
                return $model->job_category ? $model->job_category->name : '';
            })
            ->editColumn('year', function($model) {
                return $model->year ? $model->year->name : '';
            })
            ->editColumn('created_at', function($model) {
                return date('d F, Y h:i A', strtotime($model->created_at));
            })
            ->editColumn('deleted_at', function($model) {
                return date('d F, Y h:i A', strtotime($model->deleted_at));
            })
            ->rawColumns(['action', 'status', 'job_category', 'created_at', 'deleted_at', 'year'])
            ->make(true);
    }

    public function find($id)
    {
        return $this->examServiceRepository->getByUUId($id);
    }

    public function store($request)
    {
        return $this->examServiceRepository->store($request);
    }

    public function update($id, $request)
    {
        return $this->examServiceRepository->update($request, $id);
    }

    public function destroy($id)
    {
        return $this->examServiceRepository->delete($id);
    }

    public function restore($uuid)
    {
        $model = $this->examServiceRepository->getDeletedItemByUUID($uuid);
        if (!$model) {
            return response()->json([
                'status' => false,
                'message' => 'Exam not found or already active'
            ]);
        }

        $action = $this->examServiceRepository->restoreDeletedItemByUUID($model);
        if(!$action) {
            return response()->json([
                'status' => false,
                'message' => 'Something Went Wrong while Restoring.'
            ]);
        }

        return response()->json([
            'load' => true,
            'status' => true,
            'message' => 'Exam restored successfully.'
        ]);
    }
    
    public function forceDelete($uuid)
    {
        $model = $this->examServiceRepository->getDeletedItemByUUID($uuid);
        if (!$model) {
            return response()->json([
                'status' => false,
                'message' => 'Exam not found or already deleted.'
            ]);
        }

        $action = $this->examServiceRepository->forceDeleteItemByUUID($model);
        if(!$action) {
            return response()->json([
                'status' => false,
                'message' => 'Something went wrong while force deleting.'
            ]);
        }

        return response()->json([
            'load' => true,
            'status' => true,
            'message' => 'Exam deleted successfully.'
        ]);
    }

}