<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use App\Models\Comment;
use App\Models\Dog;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class StoreController extends Controller
{
// Controller: index method
    public function index(Request $request)
    {
        $products = Product::with('user') // Remove with('user') if user relation not exists
            ->latest()
            ->take(9)
            ->get()
            ->map(function ($product) {
                $product->type = 'product';
                return $product;
            });

        $filters = [
            'size' => ['mini' => 'کوچک', 'medium' => 'متوسط', 'large' => 'بزرگ'],
            'category' => [
                'entertainment' => 'لوازم سرگرمی سگ',
                'training'      => 'لوازم آموزشی سگ',
                'lorem1'        => 'لورم',
                'lorem2'        => 'لورم'
            ]
        ];

    $blogs = Blog::where('status', 'published')->orderBy('created_at', 'desc')->get();

        return view('stores.index', compact('products', 'filters', 'blogs'));
    }



    public function show($id)
    {
        // Get the main product
        $product = Product::with('category')->findOrFail($id);

        // Get 4 recent products excluding the current one
        $recentProducts = Product::where('id', '!=', $product->id)
                                ->latest()
                                ->take(4)
                                ->get();

        // Get comments related to the product
        $comments = Comment::with('user')
                           ->where('user_id', $product->id)
                           ->latest()
                           ->get();

        return view('frontend.pages.products.show', compact('product', 'recentProducts', 'comments'));
    }
}
