<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Subcategory;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class SubcategoryController extends Controller
{

    public function index()
    {
        $categories = Category::orderBy('priority', 'asc')->get();
        $subcategories = Subcategory::orderBy('priority', 'asc')->get();
        return view('backend.subcategory.index', compact('categories', 'subcategories'));
    }


    public function store(Request $request)
    {
        $request->validate([
            'category' => 'required',
            'subcategory_name' => 'required|unique:subcategories,name|max:255',
        ]);

        //Slug Generation
        $slug = Str::slug($request->subcategory_name);

        // Priority Generation
        $priority = Subcategory::max('priority') + 1;

        // Store the subcategory in the database
        Subcategory::create([
            'category_id' => $request->category,
            'name' => $request->subcategory_name,
            'slug' => $slug,
            'priority' => $priority,
        ]);

        return redirect()->route('subcategory.index')->with('success', 'Subcategory added successfully.');
    }


    public function destroy(string $id)
    {
        $subcategory = Subcategory::findOrFail($id);

        try {
            // Delete subcategory
            $subcategory->delete();
        } catch (\Exception $e) {
            Log::error($e);
            return error($e->getMessage());
        }

        return response()->json(['success' => true, 'message' => 'Subcategory Deleted Successfully'], 200);
    }

    public function updateStatus($id)
    {
        $subcategory = Subcategory::find($id);
        try {
            // Update subcategory status
            $subcategory->update([
                'status' => $subcategory->status == '1' ? '0' : '1',
            ]);
        } catch (\Exception $e) {
            Log::error($e);
            return error($e->getMessage());
        }

        return response()->json(['success' => true, 'message' => 'Subcategory status Updated Successfully'], 200);
    }

        public function updateAjax(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:subcategories,name,' . $request->id,
            'slug' => 'required|unique:subcategories,slug,' . $request->id,
            'priority' => 'required|integer'
        ]);

        // Return the validation errors
        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ]);
        }

        // Update the subcategory
        $subcategory = Subcategory::findOrFail($request->id);
        $subcategory->update([
            'name' => $request->name,
            'slug' => $request->slug,
            'priority' => $request->priority,
        ]);

        Session::flash('success', 'Subcategory updated successfully');

        return response()->json([
            'success' => true
        ]);
    }
}
