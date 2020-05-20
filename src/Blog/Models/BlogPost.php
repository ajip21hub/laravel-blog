<?php

namespace Ngodink\Blog\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BlogPost extends Model
{
    use SoftDeletes;
    
    /**
     * The database table used by the model.
     */
    protected $table;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'slug', 'img', 'title', 'content', 'author_id', 'commentable', 'visibled', 'published_at'
    ];

    /**
     * The attributes that should be hidden for arrays.
     */
    protected $hidden = [
        'author_id'
    ];

    /**
     * The attributes that define value is a instance of carbon.
     */
    protected $dates = [
        'published_at', 'deleted_at', 'created_at', 'updated_at'
    ];

    /**
     * Creates a new instance of the model.
     */
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->table = config('blog.table.prefix', 'blog_').'posts';
    }

    /**
     * This belongsTo author.
     */
    public function author () {
        return $this->belongsTo(config('blog.user_model', config('auth.providers.users.model')), 'author_id');
    }

    /**
     * This belongsToMany likes.
     */
    public function likes () {
        return $this->belongsToMany(config('blog.user_model', config('auth.providers.users.model')), config('blog.table.prefix', 'blog_').'post_likes', 'post_id', 'liker_id');
    }

    /**
     * This hasMany comments.
     */
    public function comments () {
        return $this->hasMany(BlogPostComment::class, 'post_id');
    }

    /**
     * This belongsToMany categories.
     */
    public function categories () {
        return $this->belongsToMany(BlogCategory::class, config('blog.table.prefix', 'blog_').'post_categories', 'post_id', 'category_id');
    }

    /**
     * This hasMany tags.
     */
    public function tags () {
        return $this->hasMany(BlogPostTag::class, 'post_id');
    }

    /**
     * This hasMany metas.
     */
    public function metas () {
        return $this->hasMany(BlogPostMeta::class, 'post_id');
    }
    
    /**
     * Scope find by slug.
     */
    public function scopeFindBySlug ($query, $slug) {
        return $query->where('slug', $slug)->first();
    }

    /**
     * Scope where published.
     */
    public function scopePublished ($query) {
        return $query->whereNotNull('published_at')->whereDate('published_at', '<=', now());
    }

    /**
     * Scope where unpublished.
     */
    public function scopeUnpublished ($query) {
        return $query->whereNull('published_at')->orWhere(function ($term) {
            return $term->scheduled();
        });
    }

    /**
     * Scope where scheduled.
     */
    public function scopeScheduled ($query) {
        return $query->whereDate('published_at', '>', now());
    }

    /**
     * Scope where authored by.
     */
    public function scopeAuthoredBy ($query, $id) {
        return $query->whereIn('author_id', (array) $id);
    }

    /**
     * Get meta.
     */
    public function getMetas () {
        return $this->metas ?? [];
    }

    /**
     * Get meta.
     */
    public function getMeta ($key) {
        return $this->metas()->findByKey($key)->content ?? null;
    }

    /**
     * Set meta.
     */
    public function setMeta ($key, $value) {
        $this->metas()->updateOrCreate(['key' => $key], ['content' => $value]);
        return $this;
    }
    
    /**
     * Querying the default metas.
     */
    public function insertDefaultMetas() {
        $class = config('blog.post_meta_class');
        $metas = [];
        foreach ((new $class())->defaultMetas($this) as $key => $content) {
            $metas[] = compact('key', 'content');
        }

        $this->metas()->createMany($metas);
        return $this;
    }

    /**
     * Scope where tag is.
     */
    public function scopeWhereTag ($query, $tag) {
        return $query->whereHas('tags', function ($tags) use ($tag) {
            return $tags->where('name', $tag);
        });
    }

    /**
     * Scope where tag is.    
     */
    public function scopeWhereLikedBy ($query, $id) {
        return $query->whereHas('likes', function ($tags) use ($id) {
            return $tags->where('liker_id', (array) $id);
        });
    }

    /**
     * Scope where category is.
     */
    public function scopeWhereCategoryIn ($query, $id) {
        return $query->whereHas('categories', function ($categories) use ($id) {
            return $categories->whereIn('id', (array) $id);
        });
    }
}
