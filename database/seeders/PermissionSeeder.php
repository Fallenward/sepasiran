<?php

namespace Database\Seeders;

use Faker\Factory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use App\Helpers\GenralHelpers;

class PermissionSeeder extends Seeder
{
    private const CRUD_DEF = ['create', 'read', 'update', 'delete'];

    private const DEFAULT_CRUD_RESOURCES = [
        'roles', 'permissions', 'users', 'levels', 'sliders', 'courses', 'types', 'categories', 'days', 'blogs',
        'comments', 'certificates', 'discounts', 'settings', 'notifications', 'course_participates', 'lives',
        'menus', 'exams', 'quizzes', 'quiz_questions', 'quiz_answers', 'transactions', 'chats', 'masters', 'my_courses','students', 'dashboard'
    ];


    public function run(): void
    {
        DB::transaction(function () {
            // ایجاد مجوزها برای تمام CRUD منابع
            foreach (self::DEFAULT_CRUD_RESOURCES as $resource) {
                foreach (self::CRUD_DEF as $action) {
                    Permission::create([
                        'name' => "$resource.$action",
                        'slug' => "$resource.$action",
                    ]);
                }
            }

            // مجوزهای خاص برای پروفایل و داشبورد
            $customPermissions = [
                'profile.read',
                'profile.update',
                'dashboard.read',
            ];

            foreach ($customPermissions as $permission) {
                Permission::create([
                    'name' => $permission,
                    'slug' => $permission,
                ]);
            }

            // ایجاد نقش‌های مدیر، استاد، و دانش‌آموز
            $adminRole = Role::create(['name' => 'administrator']);
            $teacherRole = Role::create(['name' => 'teacher']);
            $studentRole = Role::create(['name' => 'student']);

            // اختصاص همه مجوزها به مدیر
            $adminRole->permissions()->attach(Permission::all());

            // مجوزهای اختصاصی **استاد**
            $teacherPermissions = Permission::whereIn('name', [
                'courses.create', 'courses.read', 'courses.update',
                'exams.create', 'exams.read', 'exams.update',
                'quizzes.create', 'quizzes.read', 'quizzes.update',
                'quiz_questions.create', 'quiz_questions.read', 'quiz_questions.update',
                'quiz_answers.create', 'quiz_answers.read', 'quiz_answers.update',
                'lives.create', 'lives.read', 'lives.update',
                'comments.read',
            ])->pluck('id');

            $teacherRole->permissions()->attach($teacherPermissions);

            // مجوزهای اختصاصی **دانش‌آموز**
            $studentPermissions = Permission::whereIn('name', [
                'courses.read', 'course_participates.create',
                'exams.read', 'quizzes.read',
                'comments.create', 'comments.read',
                'lives.read',
                'dashboard.read', 'profile.read', 'profile.update',
                'my_courses.read'
            ])->pluck('id');


            $studentRole->permissions()->attach($studentPermissions);

            // 📌 افزودن تصویر پیش‌فرض برای کاربران
            $imageDestinationFolder = 'public/users';
            $generalHelper = new GenralHelpers();
            $generalHelper->copyImage('database/seeders/images/user.svg', $imageDestinationFolder);

            $faker = Factory::create('fa_IR');

            // ایجاد کاربر **مدیر**
            $adminUser = User::create([
                'name' => 'امیر',
                'family' => 'شاه امیری',
                'photo' => 'storage/users/user.svg',
                'email' => 'amir.shahamiri1995@gmail.com',
                'password' => Hash::make('amir.shahamiri1995@gmail.com'),
                'email_verified_at' => Carbon::now(),
                'bio' => $faker->realText(2000),
            ]);

            // اختصاص نقش مدیر به کاربر مدیر
            $adminUser->roles()->attach($adminRole);

            // ایجاد کاربر **مدیر**
            $adminUser = User::create([
                'name' => 'آیناز',
                'family' => 'پورحسین',
                'photo' => 'storage/users/user.svg',
                'email' => 'pourhossein@apex.com',
                'password' => Hash::make('pourhossein@apex.com'),
                'email_verified_at' => Carbon::now(),
                'bio' => $faker->realText(2000),
            ]);

            // اختصاص نقش مدیر به کاربر مدیر
            $adminUser->roles()->attach($adminRole);

            // ایجاد کاربر **استاد**
            $teacherUser = User::create([
                'name' => 'محمد',
                'family' => 'کاظمی',
                'photo' => 'storage/users/user.svg',
                'email' => 'teacher@example.com',
                'password' => Hash::make('teacher@example.com'),
                'email_verified_at' => Carbon::now(),
                'bio' => $faker->realText(1500),
            ]);

            // اختصاص نقش استاد به کاربر استاد
            $teacherUser->roles()->attach($teacherRole);

            // ایجاد کاربر **دانش‌آموز**
            $studentUser = User::create([
                'name' => 'رضا',
                'family' => 'کریمی',
                'photo' => 'storage/users/user.svg',
                'email' => 'student@example.com',
                'password' => Hash::make('student@example.com'),
                'email_verified_at' => Carbon::now(),
                'bio' => $faker->realText(1000),
            ]);

            // اختصاص نقش دانش‌آموز به کاربر دانش‌آموز
            $studentUser->roles()->attach($studentRole);
        });
    }
}
