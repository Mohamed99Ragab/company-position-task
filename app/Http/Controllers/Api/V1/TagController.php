<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Tags\StoreTagRequest;
use App\Http\Requests\Tags\UpdateTagRequest;
use App\Http\Resources\TagResource;
use App\Models\Tag;
use Illuminate\Http\Request;

class TagController extends Controller
{
    public $model;
    public function __construct()
    {
        $this->model = new Tag();
    }

    public function index()
    {
        $tags = $this->model->paginate();

        return TagResource::collection($tags);
    }





    public function store(StoreTagRequest $request)
    {
        $this->model->create($request->validated());

        return $this->successResponse('Tag created successfully');
    }


    public function show($id)
    {
        $tag = $this->model->findOrFail($id);
        return $this->successResponse(data: new TagResource($tag));

    }





    public function update(UpdateTagRequest $request, $id)
    {

        $tag = $this->model->findOrFail($id);

        $tag->update($request->validated());

        return $this->successResponse('Tag Updated successfully');


    }


    public function destroy($id)
    {
        $tag = $this->model->findOrFail($id);
        $tag->delete();
        return $this->successResponse('Tag Deleted successfully');


    }
}
