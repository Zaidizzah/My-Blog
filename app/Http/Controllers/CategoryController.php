<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Posts;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $resources = [
            'title' => 'List of Categories',
            'subtitle' => 'List of Categories',
            'breadcrumb' => [
                'Home' => '/',
                'Dashboard' => '/dashboard',
                'List of Categories' => '/category',
            ],
            'javascript' => [
                [
                    'src' => 'scripts.js',
                    'base_path' => 'resources/category/js/'
                ]
            ]
        ];

        return view('dashboard.category.index')->with([
            ...$resources,
            'categories' => Category::paginate(5)->withQueryString()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'name' => 'required|string|max:100|unique:categories'
            ]
        );

        if ($validator->fails()) {
            return redirect('/category')->with('message', toast('Invalid creating category details', 'error'))->withInput();
        }

        $validated = $validator->validated();
        $validated['name'] = ucwords($validated['name']);
        $validated['slug'] = Str::slug($validated['name']);

        Category::create($validated);

        return redirect('/category')->with('message', toast('Category created successfully!', 'success'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $category = Category::findOrFail($id);

        $validator = Validator::make(
            $request->all(),
            [
                'name' => 'required|string|max:100|unique:categories,name,' . $category->id,
            ]
        );

        if ($validator->fails()) {
            return redirect('/category')->with('message', toast('Invalid updating category details', 'error'))->withInput();
        }

        $validated = $validator->validated();
        $validated['name'] = ucwords($validated['name']);
        $validated['slug'] = Str::slug($validated['name']);

        $category->update($validated);

        return redirect('/category')->with('message', toast('Category updated successfully!', 'success'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $category = Category::findOrFail($id);
        $category->delete();

        // Switch the category to 'Unsorted'.
        $unsorted_category_id = Category::where('name', 'Unsorted')->first()->id;
        Posts::where('category_id', $category->id)->update(['category_id' => $unsorted_category_id]);

        return redirect('/category')->with('message', toast('Category deleted successfully!', 'success'));
    }
}
