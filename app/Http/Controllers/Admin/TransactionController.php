<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\TransactionRequest;
use App\Models\Transaction;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class TransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $transactions = Transaction::paginate();

        return view('admin.transaction.index', compact('transactions'))
            ->with('i', ($request->input('page', 1) - 1) * $transactions->perPage());
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $transaction = new Transaction();

        return view('admin.transaction.create', compact('transaction'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(TransactionRequest $request): RedirectResponse
    {
        Transaction::create($request->validated());

        return Redirect::route('admin.transactions.index')
            ->with('success', 'Transaction created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id): View
    {
        $transaction = Transaction::find($id);

        return view('admin.transaction.show', compact('transaction'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $transaction = Transaction::find($id);

        return view('admin.transaction.edit', compact('transaction'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(TransactionRequest $request, Transaction $transaction): RedirectResponse
    {
        $transaction->update($request->validated());

        return Redirect::route('admin.transactions.index')
            ->with('success', 'Transaction updated successfully');
    }

    public function destroy($id): RedirectResponse
    {
        Transaction::find($id)->delete();

        return Redirect::route('admin.transactions.index')
            ->with('success', 'Transaction deleted successfully');
    }
}
