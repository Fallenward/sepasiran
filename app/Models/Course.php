<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Course
 *
 * @property $id
 * @property $title
 * @property $slug
 * @property $short_description
 * @property $description
 * @property $thumbnail
 * @property $instructor_id
 * @property $category_id
 * @property $level_id
 * @property $price
 * @property $discount_price
 * @property $status
 * @property $is_featured
 * @property $has_certificate
 * @property $language
 * @property $video_demo_url
 * @property $duration
 * @property $view_count
 * @property $created_at
 * @property $updated_at
 *
 * @property Cart[] $carts
 * @property CategoryCourse[] $categoryCourses
 * @property Certificate[] $certificates
 * @property CommentAnswer[] $commentAnswers
 * @property CourseParticipant[] $courseParticipants
 * @property CourseRating[] $courseRatings
 * @property Exam[] $exams
 * @property Homework[] $homeworks
 * @property Lesson[] $lessons
 * @property License[] $licenses
 * @property Life[] $lives
 * @property Quiz[] $quizzes
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class Course extends Model
{
    
    protected $perPage = 20;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['title', 'slug', 'short_description', 'description', 'thumbnail', 'instructor_id', 'category_id', 'level_id', 'price', 'discount_price', 'status', 'is_featured', 'has_certificate', 'language', 'video_demo_url', 'duration', 'view_count'];


    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function carts()
    {
        return $this->hasMany(\App\Models\Cart::class, 'id', 'course_id');
    }
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function categoryCourses()
    {
        return $this->hasMany(\App\Models\CategoryCourse::class, 'id', 'course_id');
    }
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function certificates()
    {
        return $this->hasMany(\App\Models\Certificate::class, 'id', 'course_id');
    }
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function commentAnswers()
    {
        return $this->hasMany(\App\Models\CommentAnswer::class, 'id', 'course_id');
    }
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function courseParticipants()
    {
        return $this->hasMany(\App\Models\CourseParticipant::class, 'id', 'course_id');
    }
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function courseRatings()
    {
        return $this->hasMany(\App\Models\CourseRating::class, 'id', 'course_id');
    }
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function exams()
    {
        return $this->hasMany(\App\Models\Exam::class, 'id', 'course_id');
    }
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function homeworks()
    {
        return $this->hasMany(\App\Models\Homework::class, 'id', 'course_id');
    }
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function lessons()
    {
        return $this->hasMany(\App\Models\Lesson::class, 'id', 'course_id');
    }
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function licenses()
    {
        return $this->hasMany(\App\Models\License::class, 'id', 'course_id');
    }
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function lives()
    {
        return $this->hasMany(\App\Models\Life::class, 'id', 'course_id');
    }
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function quizzes()
    {
        return $this->hasMany(\App\Models\Quiz::class, 'id', 'course_id');
    }
    
}
