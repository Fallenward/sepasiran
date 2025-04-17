<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Dog
 *
 * @property $id
 * @property $title
 * @property $color
 * @property $age
 * @property $birth_date
 * @property $cover_type
 * @property $eye_color
 * @property $shajare
 * @property $breed_id
 * @property $father_name
 * @property $father_breed_id
 * @property $mother_name
 * @property $mother_breed_id
 * @property $maternal_grandmother_name
 * @property $maternal_grandmother_breed_id
 * @property $maternal_grandfather_name
 * @property $maternal_grandfather_breed_id
 * @property $paternal_grandmother_name
 * @property $paternal_grandmother_breed_id
 * @property $paternal_grandfather_name
 * @property $paternal_grandfather_breed_id
 * @property $created_at
 * @property $updated_at
 *
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class Dog extends Model
{
    
    protected $perPage = 20;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['title', 'color', 'age', 'birth_date', 'cover_type', 'eye_color', 'shajare', 'breed_id', 'father_name', 'father_breed_id', 'mother_name', 'mother_breed_id', 'maternal_grandmother_name', 'maternal_grandmother_breed_id', 'maternal_grandfather_name', 'maternal_grandfather_breed_id', 'paternal_grandmother_name', 'paternal_grandmother_breed_id', 'paternal_grandfather_name', 'paternal_grandfather_breed_id'];


}
