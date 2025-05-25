<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use App\Models\Category;
use Illuminate\Http\Request;
use PhpParser\Comment;

class BlogController extends Controller
{
    public function index()
    {
        $blogs = Blog::paginate(10);
        $title = 'وبلاگ';
        return view('frontend.pages.blogs.index', compact('blogs','title'));
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
