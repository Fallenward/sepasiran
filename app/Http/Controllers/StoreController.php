<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use App\Models\Comment;
use App\Models\Product;
use Illuminate\Http\Request;

class StoreController extends Controller
{
    public function index(Request $request)
    {
        $products = Product::with('user')
            ->latest()
            ->paginate(12)
            ->through(function ($product) {
                $product->type = 'product';

                return $product;
            });

        $filters = [
            'size' => ['mini' => 'کوچک', 'medium' => 'متوسط', 'large' => 'بزرگ'],
            'category' => [
                'entertainment' => 'لوازم سرگرمی سگ',
                'training' => 'لوازم آموزشی سگ',
                'lorem1' => 'لورم',
                'lorem2' => 'لورم',
            ],
        ];

        $blogs = Blog::where('status', 'published')->orderBy('created_at', 'desc')->take(9)->get();

        return view('stores.index', compact('products', 'filters', 'blogs'));
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
