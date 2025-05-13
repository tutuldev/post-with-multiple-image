<h2>All Posts</h2>

<a href="{{ route('posts.create') }}">Create Post</a>

<table border="1" cellpadding="10" cellspacing="0" style="width: 100%; margin-top: 20px;">
    <thead>
        <tr>
            <th>Title</th>
            <th>Description</th>
            <th>Codes</th>
            <th>Images</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($posts as $post)
            <tr>
                <td>{{ $post->title }}</td>
                <td>{{ $post->description }}</td>

                <!-- Display Codes -->
                <td>
                    @if (!empty($post->codes))
                        @foreach ($post->codes as $code)
                            <pre>{{ $code }}</pre>
                        @endforeach
                    @else
                        No codes available
                    @endif
                </td>

                <!-- Display Images -->
                <td>
                    @if (!empty($post->images))
                        @foreach ($post->images as $image)
                            <img src="{{ asset('storage/' . $image) }}" width="100" alt="Image" style="margin: 5px;">
                        @endforeach
                    @else
                        No images available
                    @endif
                </td>

                <td>
                    <a href="{{ route('posts.edit', $post->id) }}">Edit</a> |
                    <form action="{{ route('posts.destroy', $post->id) }}" method="POST" style="display: inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" onclick="return confirm('Are you sure?')">Delete</button>
                    </form>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
