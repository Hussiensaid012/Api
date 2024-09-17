<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\PostResource;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;



class PostController extends Controller
{
    use ApiResponseTrait;

    public function index()
    {
        $posts = PostResource::collection(Post::get());
        return $this->apiresponse($posts, 'ok', 200);
    }

    public function show($id)
    {

        $post = Post::find($id);

        if ($post) {

            return $this->apiresponse(new PostResource($post), 'ok', 200);
        }

        return $this->apiresponse(null, 'The post not found', 404);
    }

    public function store(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'title' => 'required|max:255',
            'body' => 'required',
        ]);

        if ($validator->fails()) {

            return $this->apiresponse(null, $validator->errors(), 400);
        }

        $post = Post::create($request->all());

        if ($post) {

            return $this->apiresponse(new PostResource($post), 'The post save', 201);
        }

        return $this->apiresponse(null, 'The post not found', 400);
    }

    public function update(Request $request, $id)
    {

        $validator = Validator::make($request->all(), [
            'title' => 'required|max:255',
            'body' => 'required',
        ]);

        if ($validator->fails()) {

            return $this->apiresponse(null, $validator->errors(), 400);
        }

        $post = Post::find($id);
        if (!$post) {

            return $this->apiresponse(null, 'The post not found', 404);
        }



        $post->update($request->all());

        if ($post) {

            return $this->apiresponse(new PostResource($post), 'The post update', 201);
        }
    }

    public function destroy($id)
    {
        $post = Post::find($id);
        if (!$post) {

            return $this->apiresponse(null, 'The post not found', 404);
        }
        Post::destroy($id);
        if ($post) {

            return $this->apiresponse(null, 'The post Deleted', 201);
        }
    }
}
