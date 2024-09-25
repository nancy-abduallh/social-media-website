<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use App\Models\Category;
use App\Models\Post;
use App\Models\Comment;
use App\Models\Bookmark;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\StoreBlogRequest;

class BlogController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $blogs = Blog::orderBy('id', 'desc')->paginate(9);

        return view('blogs.index', compact('blogs'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('blogs.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreBlogRequest $request)
    {
        $blog = Blog::create([
            'name' => $request->input('name'),
            'description' => $request->input('description'),
            'user_id' => Auth::user()->id
        ]);
        $request->validate([
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Ensure these rules are correct
        ]);
        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('images', 'public');
            $blog->image = $imagePath; // Assign the image path to the $post object
            $blog->save();
        }

        return redirect()->route('blogs');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Blog  $blog
     * @return \Illuminate\Http\Response
     */
    public function show(Blog $blog)
    {
        $bookmark = Bookmark::where('bookmarkable_id', $blog->id)
            ->where('bookmarkable_type', 'App\Models\Blog')
            ->where('user_id', Auth::user()->id)
            ->first();

        if ($bookmark) {
            if ($bookmark->has_changes) {
                $bookmark->update(['has_changes' => false]);
            }
        }

        $posts = Post::where('blog_id', $blog->id)
            ->orderBy('id', 'desc')
            ->paginate(9);

        return view('blogs.show', compact('blog', 'posts', 'bookmark'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Blog  $blog
     * @return \Illuminate\Http\Response
     */
    public function edit(Blog $blog)
    {
        return view('blogs.edit', compact('blog'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Blog  $blog
     * @return \Illuminate\Http\Response
     */
    public function update(StoreBlogRequest $request, Blog $blog)
    {

        $request->validate([
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Validation rules
        ]);

        // Store the image if it was uploaded
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('images', 'public');
        }
        $blog->update([
            'name' => $request->input('name'),
            'image' => $imagePath ?? $blog->image, // Keep the old image if not updated
            'description' => $request->input('description')
        ]);


        return redirect()->route('blogs')->with('message', 'The blog was successfully updated!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Blog  $blog
     * @return \Illuminate\Http\Response
     */
    public function destroy(Blog $blog)
    {
        $posts = Post::where('blog_id', $blog->id)->get();

        foreach ($posts as $post) {
            Comment::where('commentable_id', $post->id)
                ->where('commentable_type', 'App\Models\Post')
                ->delete();

            Bookmark::where('bookmarkable_id', $post->id)
                ->where('bookmarkable_type', 'App\Models\Post')
                ->delete();
        }

        Bookmark::where('bookmarkable_id', $blog->id)
            ->where('bookmarkable_type', 'App\Models\Blog')
            ->delete();

        Post::where('blog_id', $blog->id)->delete();
        Category::where('blog_id', $blog->id)->delete();
        $blog->delete();

        return redirect()->route('blogs');
    }

    public function destroyAll(Request $request)
    {
        $ids = $request->ids;
        $id_array = explode(',', $ids);
        $count = 0;

        if (!$ids) {
            return redirect()->route('blogs');
        }

        foreach ($id_array as $id) {
            $recordExists = Blog::where('id', $id)
                ->where('user_id', Auth::user()->id)
                ->exists();

            if ($recordExists) $count++;
        }

        if ($count === count($id_array)) {
            // Get all posts related to the blog
            $posts = Post::whereIn('blog_id', explode(',', $ids))
                ->where('user_id', Auth::user()->id)
                ->get();

            // Removing polymorphic related data here since I can't use cascade delete
            foreach ($posts as $post) {
                // Remove any comments related to the posts
                Comment::where('commentable_id', $post->id)
                    ->where('commentable_type', 'App\Models\Post')
                    ->delete();

                // Remove any bookmarks related to the posts
                Bookmark::where('bookmarkable_id', $post->id)
                    ->where('bookmarkable_type', 'App\Models\Post')
                    ->delete();
            }

            // Remove any bookmarks related to the blogs
            Bookmark::whereIn('bookmarkable_id', explode(',', $ids))
                ->where('bookmarkable_type', 'App\Models\Blog')
                ->delete();

            // Finally, remove the selected blogs
            Blog::whereIn('id', explode(',', $ids))
                ->where('user_id', Auth::user()->id)
                ->delete();
        }

        return redirect()->route('blogs')->with(['message' => 'Removed checked items successfully!']);
    }
}
