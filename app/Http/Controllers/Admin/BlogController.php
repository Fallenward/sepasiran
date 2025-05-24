<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\BlogRequest;
use App\Models\Blog;
use App\Models\Post;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class BlogController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $blogs = Blog::paginate();

        return view('admin.blog.index', compact('blogs'))
            ->with('i', ($request->input('page', 1) - 1) * $blogs->perPage());
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $blog = new Blog();

        return view('admin.blog.create', compact('blog'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(BlogRequest $request): RedirectResponse
    {
        Blog::create($request->validated());

        return Redirect::route('admin.blogs.index')
            ->with('success', 'Blog created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $post = Post::findOrFail($id);

        // بازگشت به ویو و ارسال پست
        return view('blog.show', compact('post'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $blog = Blog::find($id);

        return view('admin.blog.edit', compact('blog'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(BlogRequest $request, Blog $blog): RedirectResponse
    {
        $blog->update($request->validated());

        return Redirect::route('admin.blogs.index')
            ->with('success', 'Blog updated successfully');
    }

    public function destroy($id): RedirectResponse
    {
        Blog::find($id)->delete();

        return Redirect::route('admin.blogs.index')
            ->with('success', 'Blog deleted successfully');
    }

        public function showById()
        {
            // Fetch most reviewed blogs (assumes you have a 'views' or 'reviews_count' column)
            $mostReviewedBlogs = Blog::where('type', 'blog')
                ->orderByDesc('views') // or 'reviews_count' depending on your schema
                ->take(3)
                ->get();

            // Fetch latest blogs (newest)
            $latestBlogs = Blog::where('type', 'blog')
                ->latest()
                ->take(6)
                ->get();

            return view('blog.index', compact('mostReviewedBlogs', 'latestBlogs'));
        }

    public function kir()
{
    // Fetch the blog by ID or abort with 404 if not found
    $blog = Blog::all(); // throws ModelNotFoundException if missing:contentReference[oaicite:0]{index=0}
    $title = 'onvan';
    // Return the Blade view with the blog data
    return view('frontend.pages.blogs.show', compact('title','blog'));
}

public function news()
{
    // Get the latest news post
    $latestNews = Blog::where('type', 'news')->latest()->first();

    // Get the next 8 news items (excluding the latest one)
    $newsItems = Blog::where('type', 'news')
        ->when($latestNews, function ($query) use ($latestNews) {
            return $query->where('id', '!=', $latestNews->id);
        })
        ->latest()
        ->take(8)
        ->get();

    return view('blog.news', compact('latestNews', 'newsItems'));
}

}
