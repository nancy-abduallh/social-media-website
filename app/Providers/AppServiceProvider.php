<?php

namespace App\Providers;

use App\Models\Blog;
use App\Models\Post;
use App\Models\Category;
use App\Models\Comment;
use App\Models\Message;
use App\Models\Bookmark;
use App\Models\Conversation;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use App\View\Components\Img;
use Illuminate\Support\Facades\Blade;


class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        View::composer('*', function ($view) {
            if (!Auth::check()) return;

            // For displaying count in dashboard sidebar
            $num_of_blogs = Blog::where('user_id', Auth::user()->id)->count();
            $num_of_posts = Post::where('user_id', Auth::user()->id)->count();
            $num_of_categories = Category::where('user_id', Auth::user()->id)->count();
            $num_of_comments = Comment::where('user_id', Auth::user()->id)->count();
            $new_requests = Auth::user()->incomingRequests()->count();
            $updated_bookmarks = Bookmark::where('user_id', Auth::user()->id)->where('has_changes', true)->count();

            // Count total new messages
            $new_messages_count = 0;
            foreach (Auth::user()->conversations as $conversation) {
                $new_messages_count += $conversation->getNewMessagesCount();
            }
            $new_messages = $new_messages_count;

            $view->with('new_requests', $new_requests)
                ->with('new_messages', $new_messages)
                ->with('updated_bookmarks', $updated_bookmarks)
                ->with('num_of_blogs', $num_of_blogs)
                ->with('num_of_posts', $num_of_posts)
                ->with('num_of_categories', $num_of_categories)
                ->with('num_of_comments', $num_of_comments);
        });
        // Register the Img component
        \Blade::component('img', Img::class);
    }
}
