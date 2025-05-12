<h2>All Posts</h2>

<a href="{{ route('posts.create') }}">Create New Post</a>

@foreach ($posts as $post)
    <div style="border:1px solid #ddd; padding:10px; margin:10px 0;">
        <h3>{{ $post->title }}</h3>
        <p>{{ Str::limit($post->description, 100) }}</p>

        {{-- Show images if available --}}
        @if ($post->images && is_array($post->images))
            <div style="display: flex; gap: 10px; margin: 10px 0;">
                @foreach ($post->images as $img)
                    <img src="{{ asset('storage/' . $img) }}" width="100" style="border-radius: 4px;">
                @endforeach
            </div>
        @endif

        <a href="{{ route('posts.show', $post->id) }}">View</a>
        <a href="{{ route('posts.edit', $post->id) }}">Edit</a>
        <form action="{{ route('posts.destroy', $post->id) }}" method="POST" style="display:inline;">
            @csrf
            @method('DELETE')
            <button type="submit">Delete</button>
        </form>
    </div>
@endforeach
