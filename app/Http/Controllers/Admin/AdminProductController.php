<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class AdminProductController extends Controller
{
        public function index(Request $request)
        {
            $products = Product::with('category') // only existing relationships
                ->orderBy('created_at', 'desc')
                ->paginate(15);

            return view('admin.products.index', compact('products'));
        }
        public function create()
        {
            $categories = Category::pluck('name', 'id'); // [id => name]
            $brands = Brand::all(); // returns a collection of Brand model objects

            return view('admin.products.create', compact('categories', 'brands'));
        }


            public function store(Request $request)
            {
                $data = $request->validate([
                    'title' => 'required|string|max:255',
                    'slug' => 'required|unique:products,slug',
                    'price' => 'required|numeric',
                    'discount_price' => 'nullable|numeric',
                    'stock' => 'required|integer',
                    'category_id' => 'nullable|exists:categories,id',
                    'brand_id' => 'nullable|exists:brands,id',
                    'description' => 'nullable|string',
                    'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
                    'is_active' => 'boolean',
                ]);

                if ($request->hasFile('image')) {
                    $data['image'] = $request->file('image')->store('products', 'public');
                }

                $data['creator_id'] = auth()->id();

                Product::create($data);

                return redirect()->route('admin.products.index')->with('success', 'محصول با موفقیت ایجاد شد');
            }

            public function destroy(Product $product)
            {
                $product->delete();

                return redirect()->route('admin.products.index')->with('success', 'محصول با موفقیت حذف شد');
            }


            public function show(Product $product)
            {
                return view('admin.products.show', compact('product'));
            }

            public function edit(Product $product)
            {
                $categories = Category::pluck('name', 'id');
                $brands = Brand::all();

                return view('admin.products.edit', compact('product', 'categories', 'brands'));
            }

            public function update(Request $request, Product $product)
            {
                $data = $request->validate([
                    'title' => 'required|string|max:255',
                    'slug' => 'required|unique:products,slug,' . $product->id,
                    'price' => 'required|numeric',
                    'discount_price' => 'nullable|numeric',
                    'stock' => 'required|integer',
                    'category_id' => 'nullable|exists:categories,id',
                    'brand_id' => 'nullable|exists:brands,id',
                    'is_active' => 'boolean',
                ]);

                $product->update($data);

                return redirect()->route('admin.products.index')->with('success', 'محصول با موفقیت ویرایش شد');
            }





}
