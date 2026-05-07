<?php 

namespace App\Services;

use Yajra\DataTables\Facades\DataTables;
use App\Repositories\Interface\HomeCarouselRepositoryInterface;

class HomeCarouselService {
    protected $repository;

    public function __construct(HomeCarouselRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function all()
    {
        return $this->repository->all();
    }

    public function dataTable()
    {
        $models = $this->all();
        return DataTables::of($models)
            ->addIndexColumn()
            ->editColumn('url', function($model) {
                return '<a href="'. $model->url .'" target="_blank">'. $model->url .'</a>';
            })
            ->editColumn('status', function ($model) {
                if($model->status == 1) {
                    $status = '<span class="badge badge-success">Publish</span>';
                } else {
                    $status = '<span class="badge badge-warning">Unpublish</span>';
                }

                return $status;
            })
            ->addColumn('action', function ($model) {
                return view('portal.home-carousel.action', compact('model'));
            })
            ->editColumn('created_at', function($model) {
                return date('d F, Y h:i A', strtotime($model->created_at));
            })
            ->rawColumns(['action', 'url', 'status', 'created_at'])
            ->make(true);
    }

    public function find($id)
    {
        return $this->repository->getById($id);
    }

    public function store($request)
    {
        return $this->repository->store($request);
    }

    public function update($id, $request)
    {
        return $this->repository->update($request, $id);
    }

    public function destroy($id)
    {
        return $this->repository->delete($id);
    }
}