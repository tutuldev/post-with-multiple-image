<h2>All Posts</h2>

<a href="{{ route('posts.create') }}">Create New Post</a>
<br><br>

@foreach ($posts as $post)
    <div style="border: 1px solid #ccc; padding: 15px; margin-bottom: 20px;">
        <h3>{{ $post->title }}</h3>
        <p><strong>Description:</strong> {{ $post->description }}</p>

        @if (!empty($post->code_titles) && !empty($post->codes))
            <div>
                <h4>Code Blocks:</h4>
                @foreach ($post->code_titles as $index => $codeTitle)
                    <div style="margin-bottom: 10px;">
                        <strong>{{ $codeTitle }}</strong>
                        <pre style="background: #f4f4f4; padding: 10px; border: 1px solid #ddd;">
{{ $post->codes[$index] ?? '' }}
                        </pre>
                    </div>
                @endforeach
            </div>
        @endif

        @if (!empty($post->images))
            <div style="display: flex; gap: 10px; flex-wrap: wrap;">
                @foreach ($post->images as $img)
                    <img src="{{ asset('storage/' . $img) }}" alt="Post Image" style="width: 100px;">
                @endforeach
            </div>
        @endif

        <br>
        <a href="{{ route('posts.show', $post->id) }}">Show</a> |
        <a href="{{ route('posts.edit', $post->id) }}">Edit</a> |
        <form action="{{ route('posts.destroy', $post->id) }}" method="POST" style="display: inline;">
            @csrf
            @method('DELETE')
            <button type="submit" onclick="return confirm('Are you sure?')">Delete</button>
        </form>
    </div>
@endforeach
