<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\PostRequest;
use App\Http\Resources\PostCollection;
use App\Http\Resources\PostResource;
use App\Models\Post;
use Error;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function getAll(Request $request)
    {
        if ($request['search']) {
            $posts = Post::search($request['search'])->paginate(5);
        } else {
            $posts = Post::paginate(5);
        }

        return new PostCollection($posts);
    }

    public function findOne(Post $post)
    {
        return new PostResource($post);
    }

    public function add(PostRequest $request)
    {
        $data = $request->validated();
        $newPost = new Post();
        $newPost->user_id = $request->user()->id;
        $newPost->title = $data['title'];
        $newPost->description = $data['description'];
        $newPost->save();
        $newPost->categories()->sync($data['categories']);

        return (new PostResource($newPost))->additional([
            'message' => 'post has been added successfuly',
        ]);
    }

    public function update(PostRequest $request, $id)
    {
        $data = $request->validated();
        $post = Post::find($id);
        $post->title = $data['title'];
        $post->description = $data['description'];
        $post->user_id = $request->user()->id;
        $post->categories()->sync($data['categories']);
        $post->save();
        $new = Post::find($id);

        return (new PostResource($new))->additional([
            'message' => 'post has been updated successfuly',
        ]);
    }

    public function delete($id)
    {
        try {
            Post::find($id)->delete();

            return response()->json([
                'status' => true,
                'message' => 'delete post successfuly',
            ], 200);
        } catch (Error $e) {
            return response()->json([
                'status' => false,
                'message' => 'failed delete, post not find',
            ], 400);
        }
    }
}
