<?php

namespace App\Http\Controllers;

use App\Models\BlogRating;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Http\Requests\BlogRatingRequest;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class BlogRatingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $blogRatings = BlogRating::paginate();

        return view('blog-rating.index', compact('blogRatings'))
            ->with('i', ($request->input('page', 1) - 1) * $blogRatings->perPage());
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $blogRating = new BlogRating();

        return view('blog-rating.create', compact('blogRating'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(BlogRatingRequest $request): RedirectResponse
    {
        BlogRating::create($request->validated());

        return Redirect::route('blog-ratings.index')
            ->with('success', 'BlogRating created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id): View
    {
        $blogRating = BlogRating::find($id);

        return view('blog-rating.show', compact('blogRating'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $blogRating = BlogRating::find($id);

        return view('blog-rating.edit', compact('blogRating'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(BlogRatingRequest $request, BlogRating $blogRating): RedirectResponse
    {
        $blogRating->update($request->validated());

        return Redirect::route('blog-ratings.index')
            ->with('success', 'BlogRating updated successfully');
    }

    public function destroy($id): RedirectResponse
    {
        BlogRating::find($id)->delete();

        return Redirect::route('blog-ratings.index')
            ->with('success', 'BlogRating deleted successfully');
    }
}
