<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Comment
 *
 * @property $id
 * @property $name
 * @property $family
 * @property $email
 * @property $phone
 * @property $user_id
 * @property $blog_id
 * @property $course_id
 * @property $content
 * @property $rate
 * @property $status
 * @property $created_at
 * @property $updated_at
 *
 * @property CommentAnswer[] $commentAnswers
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class Comment extends Model
{

    public const STATUS_PENDING = 'pending';
    public const STATUS_APPROVED = 'approved';
    public const STATUS_REJECTED = 'rejected';

    protected $perPage = 20;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['name', 'family', 'email', 'phone', 'user_id', 'blog_id', 'course_id', 'content', 'rate', 'status'];


    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function commentAnswers()
    {
        return $this->hasMany(\App\Models\CommentAnswer::class, 'id', 'comment_id');
    }

}
