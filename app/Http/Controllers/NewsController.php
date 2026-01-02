<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\News;
use App\Models\Category;
use App\Models\NewsMeta;
use App\Models\Subcategory;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Jobs\FacebookPostJob;
use App\Jobs\TelegramPostJob;
use App\Jobs\PostToTwitterJob;
use App\Traits\ImageSaveTrait;
use App\Jobs\FacebookPostDeleteJob;
use Illuminate\Support\Facades\Log;
use App\Services\TwitterService;

class NewsController extends Controller
{
    // use Image Process Trait;
    use ImageSaveTrait;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $newses = News::latest()->get();
        return  view('backend.news.index', compact('newses'));
    }
    public function getList(Request $request)
    {
        $search = $request->input('search');
        $page   = $request->page ?? 1;
        $limit  = 10;
        $offset = ($page - 1) * $limit;
        $query = News::latest();

        // ✅ Search handle
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('id', 'like', "%{$search}%")
                    ->orWhere('title', 'like', "%{$search}%");
            });
        }

        $total = $query->count();

        $newses = $query->skip($offset)->take($limit)->get();

        return response()->json([
            'data'    => view('backend.news.news_rows', [
                'newses' => $newses,
                'page'   => $page,
                'limit'  => $limit,
                'offset' => $offset,
            ])->render(),
            'hasMore' => $total > $offset + $limit,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::all();
        return view('backend.news.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'title' => 'required',
            'category' => 'required',
            'short_description' => 'required',
            'description' => 'required',
        ]);

        $slug = substr(Str::slug($request->title), 0, 40);

        if (News::where('slug', $slug)->exists()) {
            $slug = $slug . '-' . random_int(000, 999);
        }

        if ($request->hasFile('image')) {
            $image_path = $this->saveImage('news', $request->file('image'), 900, 500);
        }

        $news = News::create([
            'title' => $request->title,
            'category_id' => $request->category,
            'subcategory_id' => $request->subcategory,
            'slug' => $slug,
            'short_description' => $request->short_description,
            'description' => $request->description,
            'image' => $image_path ?? null,
            'video_url' => $request->video_url,
            'status' => $request->status,
            'scheduled_at' => $request->scheduled_at,
        ]);

        $url = url('/news/' . $news->slug);

        $message = "{$news->title}\n\n"
            . "{$news->short_description}\n\n"
            . "Read more {$url}";

        $imageUrl = $news->image ? asset($news->image) : null;
        $twitterImageUrl = $news->image ? public_path($news->image) : null;

        if ($request->status === 'published') {
            FacebookPostJob::dispatch($news, $message, $imageUrl);
            // TelegramPostJob::dispatch($message, $imageUrl);
            PostToTwitterJob::dispatch($message, $twitterImageUrl);
        }


        if ($request->meta_title != null) {
            NewsMeta::create([
                'news_id' => $news->id,
                'title' => $request->meta_title,
                'description' => $request->meta_description,
                'tags' => $request->tags,
            ]);
        }

        return redirect()->route('news.index')->with('success', 'News created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $news = News::find($id);
        return view('backend.news.show', compact('news'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $news = News::find($id);
        $categories = Category::all();
        $subcategories = Subcategory::where('category_id', $news->category_id)->get();
        return  view('backend.news.edit', compact('news', 'categories', 'subcategories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'title' => 'required',
            'category' => 'required',
            'short_description' => 'required',
            'description' => 'required',
        ]);

        $news = News::findOrFail($id);

        $oldStatus = $news->status;

        // slug
        $slug = substr(Str::slug($request->title), 0, 40);
        if (News::where('slug', $slug)->where('id', '!=', $news->id)->exists()) {
            $slug .= '-' . random_int(100, 999);
        }

        // image
        $image_path = $news->image;
        if ($request->hasFile('image')) {
            $this->deleteImage($news->image);
            $image_path = $this->saveImage('news', $request->file('image'), 900, 500);
        }

        // UPDATE FIRST
        $news->update([
            'title' => $request->title,
            'category_id' => $request->category,
            'subcategory_id' => $request->subcategory,
            'slug' => $slug,
            'short_description' => $request->short_description,
            'description' => $request->description,
            'image' => $image_path,
            'video_url' => $request->video_url,
            'status' => $request->status,
            'scheduled_at' => $request->scheduled_at,
        ]);

        // PUBLISH CHECK
        if ($oldStatus !== 'published' && $request->status === 'published') {

            $url = url('/news/' . $news->slug);

            // Message
            $message = $news->title . "\n\n"
                . $news->short_description . "\n\n"
                . "Read more: " . $url;

            $imageUrl = $news->image ? asset($news->image) : null;

            FacebookPostJob::dispatch($news, $message, $imageUrl);
            TelegramPostJob::dispatch($message, $imageUrl);
        }

        // meta update
        if ($request->meta_title) {
            $news->meta()->update([
                'title' => $request->meta_title,
                'description' => $request->meta_description,
                'tags' => $request->tags,
            ]);
        }

        return redirect()->route('news.index')->with('success', 'News updated successfully.');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {

        $news = News::findOrFail($id);

        try {
            //Status Change
            $news->update([
                'status' => 'deleted',
            ]);
            // Delete category
            $news->delete();
        } catch (\Exception $e) {
            Log::error($e);
            return error($e->getMessage());
        }

        return response()->json(['success' => true, 'message' => 'News Deleted Successfully'], 200);
    }

    // Subcategories of a category
    public function getSubcategories($categoryId)
    {
        $subcategories = Subcategory::where('category_id', $categoryId)
            ->orderBy('priority', 'asc')
            ->get();

        return response()->json($subcategories);
    }
    public function trash()
    {
        $newses = News::onlyTrashed()->latest()->get();
        return view('backend.news.trash', compact('newses'));
    }

    public function restore($id)
    {
        $news = News::withTrashed()->find($id);

        try {
            $news->update([
                'status' => 'draft',
            ]);
            // Restore News
            $news->restore();
        } catch (\Exception $e) {
            Log::error($e);
            return error($e->getMessage());
        }

        return response()->json(['success' => true, 'message' => 'News Restored Successfully'], 200);
    }

    public function permanentlydelete(string $id)
    {
        $news = News::withTrashed()->find($id);

        try {
            // Delete FB Post
            FacebookPostDeleteJob::dispatch($news->fb_post_id);
            // Delete Image
            $this->deleteImage($news->image);

            // Delete category
            $news->forceDelete();
        } catch (\Exception $e) {
            Log::error($e);
            return error($e->getMessage());
        }

        return response()->json(['success' => true, 'message' => 'News Permanently Deleted Successfully'], 200);
    }
}
