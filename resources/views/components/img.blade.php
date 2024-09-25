@if($post && $post->image_url)
    <img src="{{ $post->image_url }}" alt="Post Image">
@else
    <p>No image available</p>
@endif
