<?php

namespace Ngodink\Blog\Observers;

use Str;
use Auth;
use Ngodink\Blog\Models\BlogPost;
use Ngodink\Blog\Models\BlogPostMeta;

class BlogPostObserver
{
    /**
     * Handle the BlogPost "saving" event.
     */
    public function saving(BlogPost $post)
    {
        $post->slug = Str::slug($post->title);
        $post->author_id = (!$post->author_id) ? (Auth::check() ? Auth::id() : null) : null;
    }

    /**
     * Handle the BlogPost "saved" event.
     */
    public function saved(BlogPost $post)
    {
        $post->insertDefaultMetas($post);
    }
}