@extends('dashboard')

@section('content')

    <div class="flex flex-col py-3">
        <h2 class="text-2xl font-bold">{{ $posts->total() }} {{ $posts->total() == 1 ? 'Post' : 'Posts' }}</h2>
        <ul class="my-3">
            <li><a href="{{ route('posts.create') }}" class="transition-all duration-150 cursor-pointer font-black text-sm uppercase tracking-widest text-teal-500 hover:text-teal-700 underline">New post</a></li>
        </ul>
    </div>

    @if (count($posts) > 0)
        @if ($posts->hasPages())
            <div class="pb-6">
                {{ $posts->appends(request()->input())->links() }}
            </div>
        @endif
        
        <div class="overflow-x-auto rounded-sm bg-gray-200 mb-3">
            <table class="w-full text-left table-fixed">
                <thead>
                    <tr class="border-b-2 bg-slate-300 border-slate-400">
                        <th class="py-3 px-6 w-20">
                            <div class="flex items-center">
                                <input type="checkbox" id="check-all" data-type="posts">
                            </div>
                        </th>
                        <th class="py-2 px-2 sm:py-3 sm:px-6">Title</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($posts as $post)
                        <tr class="border-b border-gray-300 last:border-b-0 hover:bg-teal-100 bg-slate-100 odd:bg-slate-200">
                            <td class="py-3 px-6">
                                <div class="flex items-center">
                                    <input type="checkbox" class="remove-checkbox" data-post-id="{{ $post->id }}">
                                </div>
                            </td>
                            <td class="py-2 px-2 sm:py-3 sm:px-6 break-words"><a href="{{ route('posts.edit', $post) }}">{{ $post->title }}</a></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="flex justify-end">
            <form method="POST" action="{{ route('deleteCheckedPosts') }}">
                @method('DELETE')
                @csrf

                <input type="hidden" id="ids" name="ids" />
                <x-button type="red" id="remove" class="px-3">Remove checked</x-button>
            </form>
        </div>

        @if ($posts->hasPages())
            <div class="pt-6">
                {{ $posts->appends(request()->input())->links() }}
            </div>
        @endif
    @else
        <p>No posts was found</p>
    @endif

@endsection