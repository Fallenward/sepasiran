<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Blog
 *
 * @property $id
 * @property $title
 * @property $slug
 * @property $content
 * @property $description
 * @property $image
 * @property $category_id
 * @property $author_id
 * @property $rating
 * @property $views
 * @property $likes
 * @property $status
 * @property $comments_enabled
 * @property $created_at
 * @property $updated_at
 *
 * @property BlogRating[] $blogRatings
 * @property CommentAnswer[] $commentAnswers
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class Blog extends Model
{
    
    protected $perPage = 20;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['title', 'slug', 'content', 'description', 'image', 'category_id', 'author_id', 'rating', 'views', 'likes', 'status', 'comments_enabled'];


    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function blogRatings()
    {
        return $this->hasMany(\App\Models\BlogRating::class, 'id', 'blog_id');
    }
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function commentAnswers()
    {
        return $this->hasMany(\App\Models\CommentAnswer::class, 'id', 'blog_id');
    }
    
}
