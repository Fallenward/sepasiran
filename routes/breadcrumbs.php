<?php // routes/breadcrumbs.php

use App\Models\Blog;
use App\Models\Category;
use App\Models\Master;
use Diglactic\Breadcrumbs\Breadcrumbs;
use Diglactic\Breadcrumbs\Generator as BreadcrumbTrail;

// Home
Breadcrumbs::for('home', function (BreadcrumbTrail $trail) {
    $trail->push('اپکس آنلاین', route('frontend.home'));
});

// Home > About
Breadcrumbs::for('about', function (BreadcrumbTrail $trail) {
    $trail->parent('home');
    $trail->push('درباره ما', route('frontend.about'));
});

// Home > FAQ
Breadcrumbs::for('faq', function (BreadcrumbTrail $trail) {
    $trail->parent('home');
    $trail->push('سوالات متداول', route('frontend.faq'));
});

// Home > Rules
Breadcrumbs::for('rule', function (BreadcrumbTrail $trail) {
    $trail->parent('home');
    $trail->push('قوانین و مقررات', route('frontend.rule'));
});

// Home > Courses
Breadcrumbs::for('course', function (BreadcrumbTrail $trail) {
    $trail->parent('home');
    $trail->push('دوره‌ها', route('frontend.courses'));
});

// Home > Blog
Breadcrumbs::for('blogs', function (BreadcrumbTrail $trail) {
    $trail->parent('home');
    $trail->push('وبلاگ', route('frontend.blogs.index'));
});

// Home > Blog > [Category]
Breadcrumbs::for('category', function (BreadcrumbTrail $trail, Category $category) {
    $trail->parent('blogs');
    $trail->push($category->title, route('frontend.categories.show', $category));
});

// Home > Blog > [Article]
Breadcrumbs::for('blogs.show', function (BreadcrumbTrail $trail, Blog $blog) {
    $trail->parent('blogs');
    $trail->push($blog->title, route('frontend.blogs.show', $blog));
});

// Home > Blog > [Category] (Category List)
Breadcrumbs::for('blogs.show.category', function (BreadcrumbTrail $trail, Category $category) {
    $trail->parent('blogs');
    $trail->push($category->name, route('frontend.blogs.index'));
});

// Home > Masters
Breadcrumbs::for('masters', function (BreadcrumbTrail $trail) {
    $trail->parent('home');
    $trail->push('اساتید', route('frontend.masters.index'));
});

// Home > Masters > [Master]
Breadcrumbs::for('masters.show', function (BreadcrumbTrail $trail, Master $master) {
    $trail->parent('masters');
    $trail->push($master->title . ' ' . $master->family, route('frontend.masters.show', $master->slug));
});

// Home > Top Grades
Breadcrumbs::for('grades', function (BreadcrumbTrail $trail) {
    $trail->parent('home');
    $trail->push('رتبه های برتر', route('frontend.grades'));
});

// Home > Top Grades > 3 numbers
Breadcrumbs::for('grades.show', function (BreadcrumbTrail $trail,$grade) {
    $trail->parent('grades');
    $trail->push($grade->title, route('frontend.grades.show',$grade->slug));
});
