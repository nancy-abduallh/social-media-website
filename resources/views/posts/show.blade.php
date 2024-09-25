@extends('layouts.app')

@section('container')
    <div class="pb-12 sm:py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg grow flex flex-col mb-3">
                <div class="bg-white border-b border-gray-200 grow">

                    <div class="w-full h-128 bg-gray-800 flex flex-col relative">
                        <div class="absolute right-0 p-6 text-gray-300 font-bold">
                            <x-bookmark :bookmark="$bookmark" :id="$post->id" :type="'post'" />
                        </div>
                        {{-- <div class="flex justify-center items-center grow">
                            <span class="uppercase text-gray-600 font-bold text-3xl">Image</span>
                        </div> --}}
                        @if($post->image)
                                <img src="{{ asset('storage/' . $post->image) }}" alt="Post Image" >
                            @else
                                <p>No image available</p>
                            @endif
                    </div>

                    <div class="flex flex-col p-6 col-span-2 pt-6 pb-12 px-8 lg:pt-12 lg:pb-24 lg:px-16">
                        <h2 class="text-2xl font-bold my-3">
                            <a href="{{ route('posts.show', $post) }}">{{ $post->title }}</a>
                        </h2>
                        <ul class="flex mb-3 flex-wrap">
                            <li>
                                <span
                                    class="bg-gray-200 text-gray-800 text-sm font-bold mr-2 px-2.5 py-0.5 rounded dark:bg-gray-700 dark:text-gray-300 whitespace-nowrap">
                                    <a href="#">{{ $post->user->name }}</a>
                                </span>
                            </li>
                            <li>
                                <span
                                    class="bg-gray-200 text-gray-800 text-sm font-bold mr-2 px-2.5 py-0.5 rounded dark:bg-gray-700 dark:text-gray-300 whitespace-nowrap">
                                    <a href="#">{{ $post->created_at->diffForHumans() }}</a>
                                </span>
                            </li>
                            <li>
                                <span
                                    class="bg-gray-200 text-gray-800 text-sm font-bold mr-2 px-2.5 py-0.5 rounded dark:bg-gray-700 dark:text-gray-300 whitespace-nowrap">
                                    <a
                                        href="#">{{ !is_null($post->category) ? $post->category->name : 'Uncategorized' }}</a>
                                </span>
                            </li>
                        </ul>

                    </div>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg grow flex flex-col mb-3">
                <div class="bg-white border-b border-gray-200 grow">
                    <div class="flex flex-col pt-6 pb-6 px-8 lg:pt-12 lg:pb-6 lg:px-16">
                        <h2 class="text-2xl font-bold my-3">Content:</h2>

                        <p class="text-lg leading-loose">{!! nl2br(e($post->content)) !!} </p>
                    </div>
                </div>
            </div>


            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg grow flex flex-col mb-3">
                <div class="bg-white border-b border-gray-200 grow">
                    <div class="flex flex-col pt-6 pb-6 px-8 lg:pt-12 lg:pb-6 lg:px-16">
                        @if ($errors->any())
                            @foreach ($errors->all() as $error)
                                <x-alert :message="$error" :type="'danger'"></x-alert>
                            @endforeach
                            </ul>
                        @endif


                        <form method="POST" action="{{ route('comments.store') }}">
                            @csrf

                            <h2 class="text-2xl font-bold my-3">Comment on "{{ $post->title }}":</h2>
                            <input type="hidden" name="post_id" value="{{ $post->id }}" />
                            <input type="hidden" name="post_type" value="post" />
                            <x-textbox class="w-full rounded mb-3" name="content" rows="5" required></x-textbox>
                            <div class="flex justify-end">
                                <x-button type="green" class="px-3">Publish</x-button>
                            </div>
                        </form>
                    </div>


                    <div class="pt-6 pb-12 px-8 lg:pt-6 lg:pb-24 lg:px-16">
                        <h2 class="text-2xl font-bold my-3">Comments ({{ $comments->total() }})</h2>

                        @foreach ($comments as $comment)
                            <div class="overflow-hidden grow flex flex-col mb-3 bg-gray-200 rounded">
                                <div class="border-b-2 border-gray-400 grow">
                                    <div class="flex flex-col p-6">
                                        <h3 class="font-bold">{{ $comment->user->name }} said
                                            ({{ $comment->created_at->diffForHumans() }}): </h3>
                                        <p>{!! nl2br(e($comment->content)) !!}</p>
                                    </div>
                                </div>
                            </div>
                        @endforeach

                        @if ($comments->hasPages())
                            <div class="pt-6 -mt-3">
                                {{ $comments->appends(request()->input())->links() }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
