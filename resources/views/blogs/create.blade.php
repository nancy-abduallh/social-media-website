@extends('dashboard')

@section('content')
    <div class="flex flex-col py-3">
        <h2 class="text-2xl font-bold">New blog</h2>
    </div>

    @if ($errors->any())
            @foreach ($errors->all() as $error)
                <x-alert :message="$error" :type="'danger'"></x-alert>
            @endforeach
        </ul>
    @endif

    <form method="POST" action="{{ route('blogs.store') }}" enctype="multipart/form-data">
        @csrf

        <div class="flex flex-col mb-3">
            <label for="name">Name:</label>
            <x-input type="text" id="name" name="name" required />
        </div>

        <div class="flex flex-col mb-3">
            <label for="description">Content:</label>
            <x-textbox name="description" id="description" cols="10" rows="7" required></x-textbox>
        </div>
        <div class="flex flex-col mb-3">
            <label for="image" :value="__('Upload Image')" />
            <input type="file" name="image" id="image" accept="image/*" required> <!-- Ensure this is present -->
        </div>
        <div class="flex justify-end">
            <x-button type="green" class="px-3">Publish</x-button>
        </div>
    </form>
@endsection
