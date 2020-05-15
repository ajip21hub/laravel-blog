<?php

namespace Ngodink\Blog\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BlogPostComment extends Model
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
        'post_id', 'content', 'commentator_id'
    ];

    /**
     * The attributes that should be hidden for arrays.
     */
    protected $hidden = [
        'commentator_id'
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
        $this->table = config('blog.table.prefix', 'blog_').'post_comments';
    }

    /**
     * This belongsTo commentator.
     */
    public function commentator () {
        return $this->belongsTo(config('blog.user_model'), 'commentator_id');
    }

    /**
     * This belongsTo post.
     */
    public function post () {
        return $this->belongsTo(BlogPost::class, 'post_id');
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
     * Scope where commented by.
     */
    public function scopeCommentedBy ($query, $id) {
        return $query->whereIn('commentator_id', (array) $id);
    }
}
