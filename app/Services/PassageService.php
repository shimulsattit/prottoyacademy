<?php 

namespace App\Services;

use Yajra\DataTables\Facades\DataTables;
use App\Repositories\Interface\PassageRepositoryInterface;

class PassageService {

    protected $passageServiceRepository;

    public function __construct(PassageRepositoryInterface $passageServiceRepository)
    {
        $this->passageServiceRepository = $passageServiceRepository;
    }

    public function all()
    {
        return $this->passageServiceRepository->all();
    }

    public function dataTable()
    {
        $models = $this->all();
        return DataTables::of($models)
            ->addIndexColumn()
            ->addColumn('action', function ($model) {
                return view('portal.passage.action', compact('model'));
            })
            ->editColumn('status', function ($model) {
                if($model->status == 1) {
                    $status = '<span class="badge badge-success">Publish</span>';
                } else {
                    $status = '<span class="badge badge-warning">Unpublish</span>';
                }

                return $status;
            })
            ->editColumn('created_at', function($model) {
                return date('d F Y h:i A', strtotime($model->created_at));
            }) 
            ->rawColumns(['action', 'status', 'created_at'])
            ->make(true);
    }

    public function binDataTable()
    {
        $models = $this->passageServiceRepository->onlyTrashed();
        return DataTables::of($models)
            ->addIndexColumn()
            ->addColumn('action', function ($model) {
                return view('portal.passage.bin-action', compact('model'));
            })
            ->editColumn('created_at', function($model) {
                return date('d F Y h:i A', strtotime($model->created_at));
            }) 
            ->editColumn('deleted_at', function($model) {
                return date('d F Y h:i A', strtotime($model->deleted_at));
            }) 
            ->editColumn('status', function ($model) {
                if($model->status == 1) {
                    $status = '<span class="badge badge-success">Publish</span>';
                } else {
                    $status = '<span class="badge badge-warning">Unpublish</span>';
                }

                return $status;
            })
            ->rawColumns(['action', 'status', 'deleted_at', 'created_at'])
            ->make(true);
    }

    public function find($id)
    {
        return $this->passageServiceRepository->getByUUID($id);
    }

    public function store($request)
    {
        return $this->passageServiceRepository->store($request);
    }

    public function update($id, $request)
    {
        return $this->passageServiceRepository->update($request, $id);
    }

    public function destroy($id)
    {
        return $this->passageServiceRepository->delete($id);
    }

    public function restore($uuid)
    {
        $model = $this->passageServiceRepository->getDeletedItemByUUID($uuid);
        if (!$model) {
            return response()->json([
                'status' => false,
                'message' => 'Passage not found or already active'
            ]);
        }

        $action = $this->passageServiceRepository->restoreDeletedItemByUUID($model);
        if(!$action) {
            return response()->json([
                'status' => false,
                'message' => 'Something Went Wrong while Restoring.'
            ]);
        }

        return response()->json([
            'load' => true,
            'status' => true,
            'message' => 'Passage restored successfully.'
        ]);
    }
    
    public function forceDelete($uuid)
    {
        $model = $this->passageServiceRepository->getDeletedItemByUUID($uuid);
        if (!$model) {
            return response()->json([
                'status' => false,
                'message' => 'Passage not found or already deleted.'
            ]);
        }

        $action = $this->passageServiceRepository->forceDeleteItemByUUID($model);
        if(!$action) {
            return response()->json([
                'status' => false,
                'message' => 'Something went wrong while force deleting.'
            ]);
        }

        return response()->json([
            'load' => true,
            'status' => true,
            'message' => 'Passage deleted successfully.'
        ]);
    }

}