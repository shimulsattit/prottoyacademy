<?php

namespace App\Repositories;

use App\Models\HomeCarousel;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;
use App\Repositories\Interface\HomeCarouselRepositoryInterface;

class HomeCarouselRepository implements HomeCarouselRepositoryInterface
{
    public function all()
    {
        return HomeCarousel::all();
    }

    public function store($request)
    {
        $validator = Validator::make($request->all(), [
            'icon'      => 'required|string',
            'title'     => 'required|string',
            'content'   => 'required|string',
            'url'       => 'required|string',
            'status'    => 'required|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'validator' => true,
                'message' => $validator->errors()
            ]);
        }

        $model = new HomeCarousel();
        $model->icon = $request->icon;
        $model->title = $request->title;
        $model->content = $request->content;
        $model->url = $request->url;
        $model->status = $request->status;
        $model->save();

        return response()->json([
            'status' => true,
            'message' => 'Record Created Successfully',
            'goto' => route('portal.home-carousel.index')
        ]);
    }

    public function getById($id)
    {
        return HomeCarousel::find($id);
    }

    public function update($request, $id)
    {
        $model = HomeCarousel::findOrFail($id);
        if(!$model) {
            return response()->json([
                'status' => false, 
                'message' => 'Record Not Found'
            ]);
        }

        $validator = Validator::make($request->all(), [
            'icon'      => 'required|string',
            'title'     => 'required|string',
            'content'   => 'required|string',
            'url'       => 'required|string',
            'status'    => 'required|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'validator' => true,
                'message' => $validator->errors()
            ]);
        }

        $model->icon = $request->icon;
        $model->title = $request->title;
        $model->content = $request->content;
        $model->url = $request->url;
        $model->status = $request->status;
        $model->save();

        return response()->json([
            'status' => true,
            'message' => 'Record Updated Successfully',
            'goto' => route('portal.home-carousel.index')
        ]);
    }

    public function delete($id)
    {
        $model = HomeCarousel::findOrFail($id);
        if(!$model) {
            return response()->json([
                'status' => false, 
                'message' => 'Record Not Found'
            ]);
        }

        $model->delete();
        
        return response()->json([
            'load' => true,
            'status' => true, 
            'message' => 'Record deleted successfully'
        ]);
    }
}