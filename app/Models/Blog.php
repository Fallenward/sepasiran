<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Category;
use App\Models\CommentAnswer;
use App\Models\Review;
use App\Models\User;
use App\Models\BlogRating;

/**
 * Class Blog
 *
 * @property int $id
 * @property string $title
 * @property string $slug
 * @property string $content
 * @property string $description
 * @property string $image
 * @property int $category_id
 * @property int $author_id
 * @property float $rating
 * @property int $views
 * @property int $likes
 * @property string $status
 * @property bool $comments_enabled
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 *
 * @property BlogRating[] $blogRatings
 * @property CommentAnswer[] $commentAnswers
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
    protected $fillable = [
        'title',
        'slug',
        'content',
        'description',
        'image',
        'category_id',
        'author_id',
        'rating',
        'views',
        'likes',
        'status',
        'comments_enabled',
    ];

    /**
     * Ratings for the blog (custom rating table).
     */
    public function blogRatings()
    {
        return $this->hasMany(BlogRating::class, 'blog_id', 'id');
    }

        public function home()
    {
        // Fetch latest blogs for slider - adjust number as needed
        $blogs = Blog::latest()->take(10)->get();

        return view('frontend.pages.home', [
            'blogs' => $blogs,
        ]);
    }

    /**
     * Alias for blogRatings relationship.
     */
    public function ratings()
    {
        return $this->hasMany(BlogRating::class, 'blog_id', 'id');
    }
    public function comments()
    {
        return $this->hasMany(Comment::class, 'blog_id', 'id');
    }

    /**
     * Accessor to get average rating.
     */
    public function getAverageRatingAttribute()
    {
        return $this->ratings()->avg('rating') ?? 0;
    }

    /**
     * Comment answers relationship.
     */
    public function commentAnswers()
    {
        return $this->hasMany(CommentAnswer::class, 'blog_id', 'id');
    }

    /**
     * Reviews relationship.
     */
    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    /**
     * Get average rating from reviews.
     */
    public function averageReviewRating()
    {
        return $this->reviews()->avg('rating') ?? 0;
    }

    /**
     * Blog belongs to a category.
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Blog belongs to an author.
     */
    public function author()
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function imageUrl(): string
    {
        $image = (string) ($this->image ?? '');
        if ($image === '') {
            return asset('images/demo/blogs/blog-01.jpg');
        }
        if (str_starts_with($image, 'http://') || str_starts_with($image, 'https://')) {
            return $image;
        }
        if (str_starts_with($image, 'images/') || str_starts_with($image, 'storage/')) {
            return asset($image);
        }

        return asset('storage/'.$image);
    }

    public function isNews(): bool
    {
        return ($this->type ?? '') === 'news';
    }
}
