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
        return  view('backend.news.index');
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

        /* ---------------- Slug ---------------- */
        $baseSlug = $request->filled('slug') ? $request->slug : $request->title;
        $slug = Str::limit(Str::slug($baseSlug), 40, '');
        $originalSlug = $slug;
        $count = 1;

        while (News::where('slug', $slug)->exists()) {
            $slug = $originalSlug . '-' . $count++;
        }

        /* ---------------- Image ---------------- */
        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $this->saveImage('news', $request->file('image'), 900, 500);
        }

        $scheduledAt = null;
        $createdAt = now();
        $status = 'published';

        /* ---------------- Schedule Logic ---------------- */
        if ($request->filled('scheduled_at')) {
            $scheduleTime = \Carbon\Carbon::parse($request->scheduled_at);

            if ($scheduleTime->isFuture()) {
                // future → schedule
                $scheduledAt = $scheduleTime;
                $status = 'scheduled';
            } else {
                // past → publish but backdate
                $createdAt = $scheduleTime;
                $status = 'published';
            }
        }


        /* ---------------- Create News ---------------- */
        $news = News::create([
            'title' => $request->title,
            'category_id' => $request->category,
            'subcategory_id' => $request->subcategory,
            'slug' => $slug,
            'short_description' => $request->short_description,
            'description' => $request->description,
            'image' => $imagePath,
            'video_url' => $request->video_url,
            'status' => $status,
            'scheduled_at' => $scheduledAt,
            'is_featured' => $request->featuredNews ? 'featured' : '',
            'is_hot' => $request->hotNews ? 'hot' : '',
            'created_at' => $createdAt,
        ]);

        $url = route('news.show', $news->slug);

        $message = "{$news->title}\n\n{$news->short_description}\n\nRead more {$url}";
        $imageUrl = $news->image ? asset($news->image) : null;

        /* ---------------- Publish Check ---------------- */
        if ($status === 'published') {
            FacebookPostJob::dispatch($news, $message, $imageUrl);
            // TelegramPostJob::dispatch($message, $imageUrl);
            PostToTwitterJob::dispatch($message);
        }

        /* ---------------- Meta Update ---------------- */
        if ($request->filled('meta_title')) {
            $news->meta()->create([
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
            'scheduled_at' => 'nullable|date',
        ]);

        $news = News::findOrFail($id);
        $oldStatus = $news->status;

        /* ---------------- Slug ---------------- */
        $baseSlug = $request->filled('slug') ? $request->slug : $request->title;
        $slug = Str::limit(Str::slug($baseSlug), 40, '');
        $originalSlug = $slug;
        $count = 1;

        while (
            News::where('slug', $slug)
            ->where('id', '!=', $news->id)
            ->exists()
        ) {
            $slug = $originalSlug . '-' . $count++;
        }

        /* ---------------- Image ---------------- */
        $imagePath = $news->image;
        if ($request->hasFile('image')) {
            $this->deleteImage($news->image);
            $imagePath = $this->saveImage('news', $request->file('image'), 900, 500);
        }

        /* ---------------- Schedule Logic ---------------- */
        $scheduledAt = null;
        $createdAt = $news->created_at;
        $status = 'published';

        if ($request->filled('scheduled_at')) {
            $scheduleTime = \Carbon\Carbon::parse($request->scheduled_at);

            if ($scheduleTime->isFuture()) {
                // future → scheduled
                $scheduledAt = $scheduleTime;
                $status = 'scheduled';
            } else {
                // past → publish + backdate
                $createdAt = $scheduleTime;
                $status = 'published';
            }
        }

        /* ---------------- Update News ---------------- */
        $news->update([
            'title' => $request->title,
            'category_id' => $request->category,
            'subcategory_id' => $request->subcategory,
            'slug' => $slug,
            'short_description' => $request->short_description,
            'description' => $request->description,
            'image' => $imagePath,
            'video_url' => $request->video_url,
            'status' => $status,
            'scheduled_at' => $scheduledAt,
            'is_featured' => $request->featuredNews ? 'featured' : '',
            'is_hot' => $request->hotNews ? 'hot' : '',
            'created_at' => $createdAt,
        ]);

        /* ---------------- Publish Check ---------------- */
        if ($oldStatus !== 'published' && $status === 'published') {

            $url = route('news.show', $news->slug);

            $message = "{$news->title}\n\n{$news->short_description}\n\nRead more: {$url}";
            $imageUrl = $news->image ? asset($news->image) : null;

            FacebookPostJob::dispatch($news, $message, $imageUrl);
            TelegramPostJob::dispatch($message, $imageUrl);
        }

        /* ---------------- Meta Update ---------------- */
        if ($request->filled('meta_title')) {
            $news->meta()->updateOrCreate(
                ['news_id' => $news->id],
                [
                    'title' => $request->meta_title,
                    'description' => $request->meta_description,
                    'tags' => $request->tags,
                ]
            );
        }

        return redirect()
            ->route('news.index')
            ->with('success', 'News updated successfully.');
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

    public function featured()
    {
        $featuredNewses = News::where('is_featured', 'featured')->latest()->get();
        $hotNewses = News::where('is_hot', 'hot')->latest()->get();
        return view('backend.news.featured', compact('featuredNewses', 'hotNewses'));
    }

    public function featuredUpdate($id)
    {

        $news = News::find($id);

        try {
            $news->update([
                'is_featured' => null,
            ]);
        } catch (\Exception $e) {
            Log::error($e);
            return error($e->getMessage());
        }

        return response()->json(['success' => true, 'message' => 'News Remove From Featured'], 200);
    }
    public function hotUpdate($id)
    {

        $news = News::find($id);

        try {
            $news->update([
                'is_hot' => null,
            ]);
        } catch (\Exception $e) {
            Log::error($e);
            return error($e->getMessage());
        }

        return response()->json(['success' => true, 'message' => 'News Remove From Hot'], 200);
    }
}
