<?php

namespace App\Repositories;

use App\Models\Passage;
use App\Models\Year;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;
use App\Repositories\Interface\PassageRepositoryInterface;

class PassageRepository implements PassageRepositoryInterface
{
    public function all()
    {
        return Passage::all();
    }

    public function onlyTrashed()
    {
        return Passage::onlyTrashed()->get();
    }

    public function store($request)
    {
        $validator = Validator::make($request->all(), [
            'name'              => 'required|string|min:4',
            'description'       => 'required|string',
            'status'            => 'required|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'validator' => true,
                'message' => $validator->errors()
            ]);
        }

        $model = new Passage();
        $model->admin_id = auth()->guard('admin')->user()->id;
        $model->uuid = (string) Str::uuid();
        $model->name = $request->name;
        $model->passage = $request->description;
        $model->status = $request->status;
        $model->save();

        return response()->json([
            'load' => true,
            'status' => true,
            'message' => 'Passage Created Successfully'
        ]);
    }

    public function getById($id)
    {
        return Passage::find($id);
    }
    
    public function getByUUId($uuid)
    {
        return Passage::where('uuid', $uuid)->first();
    }

    public function update($request, $id)
    {
        $model = Passage::where('uuid', $id)->first();
        if(!$model) {
            return response()->json([
                'status' => false, 
                'message' => 'Passage Not Found'
            ]);
        }

        $validator = Validator::make($request->all(), [
            'name'              => 'required|string|min:4',
            'description'       => 'required|string',
            'status'            => 'required|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'validator' => true,
                'message' => $validator->errors()
            ]);
        }

        $model->name = $request->name;
        $model->passage = $request->description;
        $model->status = $request->status;
        $model->save();

        return response()->json([
            'status' => true,
            'message' => 'Passage Updated Successfully',
            'goto' => route('portal.passage.index')
        ]);
    }

    public function delete($id)
    {
        $model = Passage::where('uuid', $id)->first();
        if(!$model) {
            return response()->json([
                'status' => false, 
                'message' => 'Passage Not Found'
            ]);
        }

        $model->delete();
        
        return response()->json([
            'load' => true,
            'status' => true, 
            'message' => 'Passage deleted successfully'
        ]);
    }

    public function getDeletedItemByUUID($uuid)
    {
        return Passage::onlyTrashed()->where('uuid', $uuid)->first();
    }

    public function restoreDeletedItemByUUID($model)
    {
        return $model->restore();
    }

    public function forceDeleteItemByUUID($model)
    {
        return $model->forceDelete();
    }
}
