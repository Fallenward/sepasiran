<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

/**
 * Class User
 *
 * @property $id
 * @property $name
 * @property $email
 * @property $email_verified_at
 * @property $password
 * @property $remember_token
 * @property $created_at
 * @property $updated_at
 */
class User extends Authenticatable implements MustVerifyEmail
{
    protected $perPage = 20;

    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name', 'email', 'password', 'is_admin'
    ];


    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'is_admin' => 'boolean', // تبدیل is_admin به نوع بولی
    ];


    /**
     * بررسی نقش کاربر
     *
     * @param string $role
     * @return bool
     */
    public function hasRole($role): bool
    {
        if ($role == 'administrator') {
            return $this->is_admin;
        }

        return false; // سایر نقش‌ها را هم می‌توانید اضافه کنید
    }

    /**
     * اینجا باید روابط واقعی مدل‌ها قرار گیرد.
     * به عنوان مثال، اگر مدل Blog وجود دارد و کاربر می‌تواند چندین وبلاگ داشته باشد:
     */
    public function blogs()
    {
        return $this->hasMany(\App\Models\Blog::class, 'author_id', 'id');
    }

    /**
     * روابط مشابه می‌توانند برای سایر مدل‌ها مانند Notifications و غیره ایجاد شوند.
     */

    public function notifications()
    {
        return $this->hasMany(\App\Models\Notification::class, 'receiver_id', 'id');
    }
    public function Dogs(){
        return $this->hasMany(Dog::class );
    }
}
