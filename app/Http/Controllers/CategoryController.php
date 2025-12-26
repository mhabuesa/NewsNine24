<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Jobs\FacebookPostJob;
use App\Jobs\FacebookPostDeleteJob;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{

    public function index()
    {
        $categories = Category::orderBy('priority', 'asc')->get();
        return view('backend.category.index', compact('categories'));
    }


    public function store(Request $request)
    {
        $request->validate([
            'category_name' => 'required|unique:categories,name|max:255',
        ]);

        $maxPriority = Category::max('priority') ?? 0;
        $priority = $maxPriority + 1;

        Category::create([
            'name' => $request->category_name,
            'slug' => Str::slug($request->category_name),
            'priority' => $priority,
        ]);


        return redirect()->route('category.index')->with('success', 'Category added successfully.');
    }


    public function destroy(string $id)
    {
        $category = Category::findOrFail($id);

        try {
            // Delete category
            $category->delete();
        } catch (\Exception $e) {
            Log::error($e);
            return error($e->getMessage());
        }

        return response()->json(['success' => true, 'message' => 'Category Deleted Successfully'], 200);
    }

    public function updateStatus($id)
    {
        $category = Category::find($id);
        try {
            // Update category status
            $category->update([
                'status' => $category->status == '1' ? '0' : '1',
            ]);
        } catch (\Exception $e) {
            Log::error($e);
            return error($e->getMessage());
        }

        return response()->json(['success' => true, 'message' => 'Category status Updated Successfully'], 200);
    }

    public function updateAjax(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:categories,name,' . $request->id,
            'slug' => 'required|unique:categories,slug,' . $request->id,
            'priority' => 'required|integer'
        ]);

        // Return the validation errors
        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ]);
        }

        // Update the category
        $category = Category::findOrFail($request->id);
        $category->update([
            'name' => $request->name,
            'slug' => $request->slug,
            'priority' => $request->priority,
        ]);

        Session::flash('success', 'Category updated successfully');

        return response()->json([
            'success' => true
        ]);
    }
}
