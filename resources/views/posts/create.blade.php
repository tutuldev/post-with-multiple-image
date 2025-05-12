<h2>Create Post</h2>

<form action="{{ route('posts.store') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <input type="text" name="title" placeholder="Title"><br>
    <textarea name="description" placeholder="Description"></textarea><br>
    <textarea name="code" placeholder="Code"></textarea><br>
    <input type="file" name="images[]" multiple><br>
    <button type="submit">Create</button>
</form>
