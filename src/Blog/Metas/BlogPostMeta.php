<?php

namespace Ngodink\Blog\Metas;

use Str;
use Ngodink\Blog\Models\BlogPost;

class BlogPostMeta
{
    /**
     * Default excerpt words
     */
    protected $default_excerpt_length = 20;

    /**
     * Set default metas
     */
    public function defaultMetas(BlogPost $post)
    {
    	$excerpt = Str::words(strip_tags($post->content), $this->default_excerpt_length, '');

        return [
            'excerpt'   => $excerpt,
            'seo.img' => $post->img,
            'seo.title' => $post->title,
            'seo.description' => $excerpt,
            'seo.keywords' => null
        ];
    }
}