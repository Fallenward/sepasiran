<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class CommentAnswer
 *
 * @property $id
 * @property $user_id
 * @property $blog_id
 * @property $course_id
 * @property $comment_id
 * @property $content
 * @property $status
 * @property $created_at
 * @property $updated_at
 *
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class CommentAnswer extends Model
{
    
    protected $perPage = 20;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['user_id', 'blog_id', 'course_id', 'comment_id', 'content', 'status'];


}
