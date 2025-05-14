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
}
