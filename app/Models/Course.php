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
     * @property $is_active
     *
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
        protected $fillable = ['title', 'slug', 'short_description', 'description', 'thumbnail', 'instructor_id', 'category_id', 'level_id', 'price', 'discount_price', 'status', 'is_featured', 'has_certificate', 'language', 'video_demo_url', 'duration', 'view_count', 'is_active'];


        // Course.php (Model)
    // public function curriculums()
    // {
    //     return $this->hasMany(Curriculum::class);
    // }

    // public function reviews()
    // {
    //     return $this->hasMany(Review::class);
    // }

        public function coach()
    {
        return $this->belongsTo(Coach::class);
    }

    public function level()
{
    return $this->belongsTo(Level::class);
}




    }
