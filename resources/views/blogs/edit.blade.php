@extends('dashboard')

@section('content')
    <div class="flex justify-between items-center py-3">
        <h2 class="text-2xl font-bold">Edit blog</h2>

        <form method="POST" action="{{ route('blogs.destroy', $blog) }}">
            @method('DELETE')
            @csrf

            <div class="flex">
                <x-button type="transparent">Delete</x-button>
            </div>
        </form>
    </div>

    @if ($errors->any())
        <ul>
            @foreach ($errors->all() as $error)
                <x-alert :message="$error" :type="'danger'"></x-alert>
            @endforeach
        </ul>
    @endif

    <form method="POST" action="{{ route('blogs.update', $blog) }}" enctype="multipart/form-data">
        @method('PUT')
        @csrf

        <div class="flex flex-col mb-3">
            <label for="name">Name:</label>
            <x-input type="text" id="name" name="name" value="{{ $blog->name }}" required />
        </div>

        <div class="flex flex-col mb-3">
            <label for="description">Content:</label>
            <x-textbox name="description" id="description" cols="10" rows="7"
                required>{{ $blog->description }}</x-textbox>
        </div>

        <div class="flex flex-col mb-3">
            <x-label for="image" :value="__('Upload New Image (Optional)')" />
            <x-input type="file" name="image" id="image" accept="image/*" class="mt-1" />
        </div>

        @if ($blog->image)
            <div class="flex flex-col mb-3">
                <x-label :value="__('Current Image')" />
                <img src="{{ asset('storage/' . $blog->image) }}" alt="Current Image" class="img-fluid" />
            </div>
        @endif
        <div class="flex justify-end">
            <x-button type="green" class="px-3">Update</x-button>
        </div>
    </form>
@endsection
