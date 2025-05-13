<h2>{{ $post->title }}</h2>

<p><strong>Description:</strong> {{ $post->description }}</p>

@if (!empty($post->code_titles) && !empty($post->codes))
    <div>
        <h3>Code Blocks:</h3>
        @foreach ($post->code_titles as $index => $codeTitle)
            <div style="margin-bottom: 15px;">
                <strong>{{ $codeTitle }}</strong>
                <pre style="background: #f9f9f9; padding: 10px; border: 1px solid #ccc;">
{{ $post->codes[$index] ?? '' }}
                </pre>
            </div>
        @endforeach
    </div>
@endif

@if (!empty($post->images))
    <div>
        <h3>Images:</h3>
        <div style="display: flex; gap: 10px; flex-wrap: wrap;">
            @foreach ($post->images as $img)
                <img src="{{ asset('storage/' . $img) }}" alt="Post Image" style="width: 100px;">
            @endforeach
        </div>
    </div>
@endif

<br>
<a href="{{ route('posts.edit', $post->id) }}">Edit</a> |
<a href="{{ route('posts.index') }}">Back to List</a>
