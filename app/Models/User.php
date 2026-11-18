<?php

namespace App\Models;

    use Illuminate\Database\Eloquent\Factories\HasFactory;
    use Illuminate\Database\Eloquent\Model;
    use Illuminate\Database\Eloquent\Relations\BelongsToMany;
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
    class User extends Authenticatable
    {
        protected $perPage = 20;

        /** @use HasFactory<\Database\Factories\UserFactory> */
        use HasFactory, Notifiable;

        /**
         * The attributes that are mass assignable.
         *
         * @var array<int, string>
         */
        protected $fillable = [
            'name', 'family', 'email', 'password', 'is_admin', 'phone', 'city_id', 'wallet',
        ];

        public function city(){
            return $this->hasOne(City::class);
        }



        public function product(){
            return $this->hasMany(Product::class , 'user_id');
        }


        public function speciality(){

            return $this->hasMany(Specialty::class);

        }


        public function bookings() {
            return $this->hasMany(Booking::class);
        }


            public function transactions() {
            return $this->hasMany(Transaction::class);
        }


            public function services() {
            return $this->hasMany(Service::class, 'provider_id');
        }

        // In App\Models\User.php

        public function scopeRole($query, string $role)
        {
            return $query->whereHas('roles', fn($q) => $q->where('name', $role));
        }




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
         */
            public function roles()
            {
                return $this->belongsToMany(Role::class, 'role_user');
            }

            public function hasRole(string $role): bool
            {
                return $this->roles->contains('name', $role);
            }

            public function hasAnyRole(array $roles): bool
            {
                return $this->roles->pluck('name')->intersect($roles)->isNotEmpty();
            }




        /**
         * اینجا باید روابط واقعی مدل‌ها قرار گیرد.
         * به عنوان مثال، اگر مدل Blog وجود دارد و کاربر می‌تواند چندین وبلاگ داشته باشد:
         */
/**
         * Preferred account hub for this user (provider roles beat customer).
         */
        public function dashboardRoute(): string
        {
            if (!empty($this->is_admin) || $this->hasRole('admin') || $this->hasRole('administrator')) {
                return route('admin.dashboard.index');
            }
            if ($this->hasRole('breeder')) {
                return route('breeder.dashboard');
            }
            if ($this->hasRole('coach')) {
                return route('coaches.dashboard');
            }
            if ($this->hasRole('clinic')) {
                return route('clinic.dashboard');
            }

            return route('user.dashboard');
        }

        public function primaryRoleLabel(): string
        {
            if (!empty($this->is_admin) || $this->hasRole('admin') || $this->hasRole('administrator')) {
                return 'مدیر';
            }
            if ($this->hasRole('breeder')) {
                return 'پرورش‌دهنده';
            }
            if ($this->hasRole('coach')) {
                return 'مربی';
            }
            if ($this->hasRole('clinic')) {
                return 'کلینیک';
            }

            return 'مشتری';
        }

        public function blogs()
        {
            return $this->hasMany(\App\Models\Blog::class, 'author_id', 'id');
        }

        // In User or Trainer model



        /**
         * روابط مشابه می‌توانند برای سایر مدل‌ها مانند Notifications و غیره ایجاد شوند.
         */

        public function notifications()
        {
            return $this->hasMany(\App\Models\Notification::class, 'receiver_id', 'id');
        }
        public function Dogs(){
            return $this->hasMany(Dog::class, 'user_id');
        }

        public function otp(){
            return $this->hasOne(Otp::class);
        }
    }
