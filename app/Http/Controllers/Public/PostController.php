<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Contracts\View\View;

class PostController extends Controller
{
    public function show(Post $post): View
    {
        abort_unless($post->is_published, 404);

        $relatedPosts = Post::query()
            ->published()
            ->whereKeyNot($post->id)
            ->ordered()
            ->limit(2)
            ->get();

        return view('posts.show', [
            'post' => $post,
            'relatedPosts' => $relatedPosts,
            'settings' => HomeController::resolveSettings(),
        ]);
    }
}

