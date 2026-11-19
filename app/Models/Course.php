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
        protected $fillable = ['title', 'slug', 'short_description', 'description', 'thumbnail', 'user_id', 'instructor_id', 'category_id', 'level_id', 'price', 'discount_price', 'status', 'is_featured', 'has_certificate', 'language', 'video_demo_url', 'duration', 'view_count', 'is_active'];


        // Course.php (Model)
    // public function curriculums()
    // {
    //     return $this->hasMany(Curriculum::class);
    // }

    // public function reviews()
    // {
    //     return $this->hasMany(Review::class);
    // }
    public function users(){
        return $this->belongsTo(User::class,'user_id');
    }


    public function level()
{
    return $this->belongsTo(Level::class);
}


public function category()
{
    return $this->belongsTo(Category::class);
}
public function getDisplayPrice()
{
    // If you want to show discounted price if available
    if ($this->discount_price && $this->discount_price < $this->price) {
        return '<del>' . number_format($this->price) . ' تومان</del> ' . number_format($this->discount_price) . ' تومان';
    }
    // Otherwise, just show price or "رایگان" if zero
    return $this->price == 0 ? 'رایگان' : number_format($this->price) . ' تومان';
}

public function getPriceUnit()
{
    return 'تومان';
}

public function getPaymentTypeLabel()
{
    return $this->price == 0 ? 'رایگان' : 'پولی';
}


public static function getAverageRatingForCourse($courseId)
{
    return self::where('course_id', $courseId)
               ->avg('rate') ?? 0;
}
    }
