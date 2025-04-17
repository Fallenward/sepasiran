<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Category
 *
 * @property $id
 * @property $parent_id
 * @property $name
 * @property $slug
 * @property $description
 * @property $image
 * @property $created_at
 * @property $updated_at
 *
 * @property Blog[] $blogs
 * @property CategoryCourse[] $categoryCourses
 * @property Categorymeta[] $categorymetas
 * @property Postcategory[] $postcategories
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class Category extends Model
{
    
    protected $perPage = 20;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['parent_id', 'name', 'slug', 'description', 'image'];


    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function blogs()
    {
        return $this->hasMany(\App\Models\Blog::class, 'id', 'category_id');
    }
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function categoryCourses()
    {
        return $this->hasMany(\App\Models\CategoryCourse::class, 'id', 'category_id');
    }
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function categorymetas()
    {
        return $this->hasMany(\App\Models\Categorymeta::class, 'id', 'category_id');
    }
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function postcategories()
    {
        return $this->hasMany(\App\Models\Postcategory::class, 'id', 'category_id');
    }
    
}
