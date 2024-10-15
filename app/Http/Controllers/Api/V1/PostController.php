<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Posts\StorePostRequest;
use App\Http\Requests\Posts\UpdatePostRequest;
use App\Http\Resources\PostResource;
use App\Models\Post;
use App\Models\Tag;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public $model;
    public function __construct()
    {
        $this->model = new Post();
    }
    public function index()
    {
        $posts = $this->model->with('tags')
            ->where('user_id',auth()->id())
            ->paginate();

        return PostResource::collection($posts);
    }





    public function store(StorePostRequest $request)
    {

        $filePath = $this->uploadFile($request->file('cover_image'), 'posts');

      $post = $this->model->create([
            'title'=>$request->title,
            'body'=>$request->body,
            'is_pinned'=>$request->pinned,
            'cover_image'=>$filePath,
            'user_id'=>auth()->id()
        ]);

        if ($request->tags){
            $post->tags()->sync($request->tags);
        }

        return $this->successResponse('Post Created Successfully');

    }


    public function show($id)
    {
        $post = $this->model->findOrFail($id);
        $this->authorize('view',$post);

        return new PostResource($post);
    }





    public function update(Request $request, $id)
    {


        $post = $this->model->findOrFail($id);
        $this->authorize('update',$post);

        $post->update([
            'title'=>$request->title,
            'body'=>$request->body,
            'is_pinned'=>$request->pinned,
        ]);

        if ($request->tags){
            $post->tags()->sync($request->tags);
        }
        $newFile = $request->file('cover_image');

        if ($newFile){
           $newfilePath = $this->replaceFile(newFile: $newFile,oldFilePath:$post->cover_image ,folder: 'posts');
            $post->update([
                'cover_image'=>$newfilePath
            ]);
        }

        return $this->successResponse('Post Updated successfully');
    }


    public function destroy($id)
    {
        $post = $this->model->findOrFail($id);
        $this->authorize('delete',$post);

        $post->delete();
        return $this->successResponse('Post Deleted successfully');
    }


    public function postsIsDeleted(){
        $deletedPosts = $this->model->with('tags')
            ->onlyTrashed()
            ->where('user_id',auth()->id())
            ->paginate();

        return PostResource::collection($deletedPosts);

    }

    public function restorePosts($id)
    {
        $post = $this->model->withTrashed()
            ->where('user_id',auth()->id())
            ->where('id',$id)
            ->firstOrFail();

        $post->restore();

        return $this->successResponse('Post restore Successfully');

    }
}
