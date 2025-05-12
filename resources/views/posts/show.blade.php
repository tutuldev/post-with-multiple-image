<h2>{{ $post->title }}</h2>

<p>{{ $post->description }}</p>
<pre>{{ $post->code }}</pre>

@if ($post->images)
    <h4>Images:</h4>
    @foreach ($post->images as $img)
        <img src="{{ asset('storage/' . $img) }}" width="150">
    @endforeach
@endif

<a href="{{ route('posts.edit', $post->id) }}">Edit</a>
