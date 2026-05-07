<?php 

namespace App\Services;

use Yajra\DataTables\Facades\DataTables;
use App\Repositories\Interface\YearRepositoryInterface;

class YearService {

    protected $yearServiceRepository;

    public function __construct(YearRepositoryInterface $yearServiceRepository)
    {
        $this->yearServiceRepository = $yearServiceRepository;
    }

    public function all()
    {
        return $this->yearServiceRepository->all();
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
            ->editColumn('created_at', function($model) {
                return date('d F, Y h:i A', strtotime($model->created_at));
            })
            ->addColumn('action', function ($model) {
                return view('portal.year.action', compact('model'));
            })
            ->rawColumns(['action', 'created_at', 'status'])
            ->make(true);
    }

    public function binDataTable()
    {
        $models = $this->yearServiceRepository->onlyTrashed();
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
                return view('portal.year.bin-action', compact('model'));
            })
            ->rawColumns(['action', 'created_at', 'status', 'deleted_at'])
            ->make(true);
    }

    public function find($id)
    {
        return $this->yearServiceRepository->getByUUId($id);
    }

    public function store($request)
    {
        return $this->yearServiceRepository->store($request);
    }

    public function update($id, $request)
    {
        return $this->yearServiceRepository->update($request, $id);
    }

    public function destroy($id)
    {
        return $this->yearServiceRepository->delete($id);
    }

    public function restore($uuid)
    {
        $model = $this->yearServiceRepository->getDeletedItemByUUID($uuid);
        if (!$model) {
            return response()->json([
                'status' => false,
                'message' => 'Year not found or already active'
            ]);
        }

        $action = $this->yearServiceRepository->restoreDeletedItemByUUID($model);
        if(!$action) {
            return response()->json([
                'status' => false,
                'message' => 'Something Went Wrong while Restoring.'
            ]);
        }

        return response()->json([
            'load' => true,
            'status' => true,
            'message' => 'Year restored successfully.'
        ]);
    }
    
    public function forceDelete($uuid)
    {
        $model = $this->yearServiceRepository->getDeletedItemByUUID($uuid);
        if (!$model) {
            return response()->json([
                'status' => false,
                'message' => 'Year not found or already deleted.'
            ]);
        }

        $action = $this->yearServiceRepository->forceDeleteItemByUUID($model);
        if(!$action) {
            return response()->json([
                'status' => false,
                'message' => 'Something went wrong while force deleting.'
            ]);
        }

        return response()->json([
            'load' => true,
            'status' => true,
            'message' => 'Year deleted successfully.'
        ]);
    }

}