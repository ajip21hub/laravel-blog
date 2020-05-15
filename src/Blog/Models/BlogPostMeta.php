<?php

namespace Ngodink\Blog\Models;

use Illuminate\Database\Eloquent\Model;

class BlogPostMeta extends Model
{
    /**
     * The database table used by the model.
     */
    protected $table;

    /**
     * The primary key for the model.
     */
    protected $primaryKey = 'post_id';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'key', 'content'
    ];

    /**
     * The attributes that should be hidden for arrays.
     */
    protected $hidden = [
        'post_id'
    ];

    /**
     * Indicates if the model should be timestamped.
     */
    public $timestamps = false;

    /**
     * Creates a new instance of the model.
     */
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->table = config('blog.table.prefix', 'blog_').'post_meta';
    }

    /**
     * This belongsTo post.
     */
    public function post () {
        return $this->belongsTo(BlogPost::class, 'post_id');
    }
    
    /**
     * Scope find by key.
     */
    public function scopeFindByKey ($query, $key) {
        return $query->where('key', $key)->first();
    }
}
