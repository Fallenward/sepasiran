<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use App\Models\Category;
use App\Models\Comment;
use App\Models\Product;
use Illuminate\Http\Request;

class StoreController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::query()->with('user')->where('is_active', true);

        if ($request->filled('q')) {
            $term = '%' . trim((string) $request->q) . '%';
            $query->where(function ($builder) use ($term) {
                $builder->where('title', 'like', $term)
                    ->orWhere('description', 'like', $term)
                    ->orWhere('sku', 'like', $term);
            });
        }

        if ($request->filled('category')) {
            $query->whereIn('category_id', (array) $request->input('category'));
        }

        $products = $query->latest()->paginate(9)->appends($request->query());

        $categories = Category::query()->orderBy('name')->get(['id', 'name']);
        if ($categories->isEmpty()) {
            $categories = collect([
                (object) ['id' => 1, 'name' => 'غذا و تغذیه'],
                (object) ['id' => 2, 'name' => 'لوازم بازی'],
                (object) ['id' => 3, 'name' => 'مراقبت و بهداشت'],
            ]);
        }

        $filters = [
            'category' => $categories,
        ];

        return view('stores.index', compact('products', 'filters'));
    }

    public function show($id)
    {
        $product = Product::with('category')->findOrFail($id);

        $recentProducts = Product::where('id', '!=', $product->id)
            ->latest()
            ->take(4)
            ->get();

        $comments = Comment::with('user')
            ->where('user_id', $product->id)
            ->latest()
            ->paginate(10);

        return view('frontend.pages.products.show', compact('product', 'recentProducts', 'comments'));
    }
}