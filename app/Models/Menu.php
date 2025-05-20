<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Menu
 *
 * @property $id
 * @property $name
 * @property $link
 * @property $parent_id
 * @property $created_at
 * @property $updated_at
 *
 * @property Menu[] $menuses
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class Menu extends Model
{

    protected $perPage = 20;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['name', 'link', 'parent_id'];


    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */

    public function parent() {
        return $this->belongsTo(Menu::class, 'parent_id');
    }

    public function children() {
        return $this->hasMany(Menu::class, 'parent_id');
    }

}
