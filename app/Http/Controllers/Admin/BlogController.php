<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\BlogRequest;
use App\Models\Blog;
use App\Models\Category;
use App\Models\Post;
use App\Support\AppCache;
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
        AppCache::forgetHome();

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
        AppCache::forgetHome();

        return Redirect::route('admin.blogs.index')
            ->with('success', 'Blog updated successfully');
    }

    public function destroy($id): RedirectResponse
    {
        Blog::find($id)->delete();
        AppCache::forgetHome();

        return Redirect::route('admin.blogs.index')
            ->with('success', 'Blog deleted successfully');
    }

    public function showById()
    {
        $mostReviewedBlogs = Blog::query()
            ->where('type', 'blog')
            ->orderByDesc('views')
            ->take(3)
            ->get();

        $latestBlogs = Blog::query()
            ->where('type', 'blog')
            ->latest()
            ->paginate(9);

        return view('blog.index', compact('mostReviewedBlogs', 'latestBlogs'));
    }

    public function showByOne()
{
    // Fetch the blog by ID or abort with 404 if not found
    $blog = Blog::all(); // throws ModelNotFoundException if missing:contentReference[oaicite:0]{index=0}
    $title = 'onvan';
    // Return the Blade view with the blog data
    return view('frontend.pages.blogs.show', compact('title','blog'));
}

    public function news()
    {
        $latestNews = Blog::query()->where('type', 'news')->latest()->first();

        $newsItems = Blog::query()
            ->where('type', 'news')
            ->when($latestNews, fn ($query) => $query->where('id', '!=', $latestNews->id))
            ->latest()
            ->paginate(12);

        return view('blog.news', compact('latestNews', 'newsItems'));
    }


        public function showBlog($slug)
    {
        $blog = Blog::where('slug', $slug)
            ->with(['comments' => function ($query) {
                $query->where('status', \App\Models\Comment::STATUS_APPROVED);
            }])
            ->firstOrFail();

        $latestPosts = Blog::latest()->take(5)->get();
        $categories = Category::all();
        $blogRatingCount = $blog->ratings()->count(); // Assuming you have a ratings relation

        return view('frontend.pages.blogs.show', [
            'blog' => $blog,
            'title' => $blog->title,
            'latestPosts' => $latestPosts,
            'categories' => $categories,
            'blogRatingCount' => $blogRatingCount
        ]);
    }

    public function storeComment(Request $request)
{
    $validated = $request->validate([
        'blog_id' => 'required|exists:blogs,id',
        'name' => 'required|string|max:255',
        'family' => 'required|string|max:255',
        'email' => 'required|email',
        'phone' => 'required|string|max:15',
        'comment' => 'required|string',
    ]);

    \App\Models\Comment::create([
        'blog_id' => $validated['blog_id'],
        'name' => $validated['name'] . ' ' . $validated['family'],
        'email' => $validated['email'],
        'phone' => $validated['phone'],
        'content' => $validated['comment'],
        'status' => \App\Models\Comment::STATUS_PENDING,
    ]);

    return redirect()->back()->with('success', 'نظر شما با موفقیت ارسال شد و پس از تأیید نمایش داده خواهد شد.');
}


}
