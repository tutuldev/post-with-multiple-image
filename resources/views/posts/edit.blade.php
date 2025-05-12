<h2>Edit Post</h2>

<form action="{{ route('posts.update', $post->id) }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')
    <input type="text" name="title" value="{{ $post->title }}"><br>
    <textarea name="description">{{ $post->description }}</textarea><br>
    <textarea name="code">{{ $post->code }}</textarea><br>
    <input type="file" name="images[]" multiple><br>
    <button type="submit">Update</button>
</form>

@if ($post->images)
    <h4>Existing Images:</h4>
    @foreach ($post->images as $img)
        <img src="{{ asset('storage/' . $img) }}" width="100">
    @endforeach
@endif
