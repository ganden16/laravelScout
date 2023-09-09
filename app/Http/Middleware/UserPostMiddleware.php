<?php

namespace App\Http\Middleware;

use App\Models\Post;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class UserPostMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $idPost = explode('/', $request->getRequestUri())[3];
        $post = Post::find($idPost);
        $isPostUser = $post->user_id == $request->user()->id;
        if ($isPostUser) {
            return $next($request);
        }

        return response()->json([
            'errors' => [
                'message' => "You don't own the post",
            ],
        ], 403);
    }
}
