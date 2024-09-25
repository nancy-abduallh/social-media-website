<div class="flex flex-wrap justify-center">
<a href="{{ route('blogs.show', $blog->id) }}" class="flex flex-col">
    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg grow flex flex-col">
        <div class="bg-white border-b border-gray-200 grow flex flex-col">

            @if($blog->image)
            <img src="{{ asset('storage/' . $blog->image) }}" alt="Post Image">
        @else
            <p>No image available</p>
        @endif

            <div class="p-6 grow flex flex-col">
                <h2 class="text-xl font-bold text-center">{{ $blog->name }}</h2>

                <span class="p-1 m-2 text-center font-bold">
                    <span class="bg-transparent text-800 text-xs font-bold px-2.5 py-0.5 rounded dark:bg-gray-700 dark:text-gray-300">
                        {{ $blog->user->name }}
                    </span>
                </span>
                <p class="text-center">{!! substr(nl2br(e($blog->description)), 0, 300, ) !!} .....
                    <a href="{{ route('blogs.show', $blog) }}" class="flex flex-col font-bold text-rose-500 underline">Read More</a>
                </p>

            </div>
        </div>
    </div>
</a>
</div>
