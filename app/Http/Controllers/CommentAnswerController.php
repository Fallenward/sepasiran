<?php

namespace App\Http\Controllers;

use App\Models\CommentAnswer;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Http\Requests\CommentAnswerRequest;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class CommentAnswerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $commentAnswers = CommentAnswer::paginate();

        return view('comment-answer.index', compact('commentAnswers'))
            ->with('i', ($request->input('page', 1) - 1) * $commentAnswers->perPage());
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $commentAnswer = new CommentAnswer();

        return view('comment-answer.create', compact('commentAnswer'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CommentAnswerRequest $request): RedirectResponse
    {
        CommentAnswer::create($request->validated());

        return Redirect::route('comment-answers.index')
            ->with('success', 'CommentAnswer created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id): View
    {
        $commentAnswer = CommentAnswer::find($id);

        return view('comment-answer.show', compact('commentAnswer'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $commentAnswer = CommentAnswer::find($id);

        return view('comment-answer.edit', compact('commentAnswer'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CommentAnswerRequest $request, CommentAnswer $commentAnswer): RedirectResponse
    {
        $commentAnswer->update($request->validated());

        return Redirect::route('comment-answers.index')
            ->with('success', 'CommentAnswer updated successfully');
    }

    public function destroy($id): RedirectResponse
    {
        CommentAnswer::find($id)->delete();

        return Redirect::route('comment-answers.index')
            ->with('success', 'CommentAnswer deleted successfully');
    }
}
