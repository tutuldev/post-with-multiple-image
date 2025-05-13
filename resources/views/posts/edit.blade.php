<h2>Edit Post</h2>

<form action="{{ route('posts.update', $post->id) }}" method="POST" enctype="multipart/form-data" id="editPostForm">
    @csrf
    @method('PUT')

    <input type="text" name="title" value="{{ $post->title }}"><br><br>
    <textarea name="description">{{ $post->description }}</textarea><br><br>
    <textarea name="code">{{ $post->code }}</textarea><br><br>

    <input type="file" id="imageInput" name="images[]" multiple><br><br>

    {{-- Image Preview (Old + New) --}}
    <div id="imagePreview" style="display: flex; flex-wrap: wrap; gap: 10px;"></div>

    {{-- Hidden container for tracking removed images --}}
    <div id="removedImagesContainer"></div>

    <button type="submit">Update</button>
</form>

<script>
    const oldImages = @json($post->images ?? []);
    const previewContainer = document.getElementById('imagePreview');
    const removedImagesContainer = document.getElementById('removedImagesContainer');
    const imageInput = document.getElementById('imageInput');
    const form = document.getElementById('editPostForm');

    let selectedNewFiles = [];
    let existingImages = [...oldImages];

    // Render old images
    function renderAllImages() {
        previewContainer.innerHTML = '';

        // Show existing (old) images
        existingImages.forEach((imgPath, index) => {
            const div = document.createElement('div');
            div.style.position = 'relative';

            const img = document.createElement('img');
            img.src = `/storage/${imgPath}`;
            img.style.width = '100px';
            img.style.border = '1px solid #ccc';

            const btn = document.createElement('button');
            btn.innerText = '×';
            btn.style.position = 'absolute';
            btn.style.top = '0';
            btn.style.right = '0';
            btn.style.background = 'red';
            btn.style.color = 'white';
            btn.style.border = 'none';
            btn.style.cursor = 'pointer';

            btn.addEventListener('click', () => {
                // Add to hidden input for removal
                const hidden = document.createElement('input');
                hidden.type = 'hidden';
                hidden.name = 'remove_images[]';
                hidden.value = imgPath;
                removedImagesContainer.appendChild(hidden);

                existingImages.splice(index, 1);
                renderAllImages();
            });

            div.appendChild(img);
            div.appendChild(btn);
            previewContainer.appendChild(div);
        });

        // Show new selected images
        selectedNewFiles.forEach((file, index) => {
            const reader = new FileReader();

            reader.onload = function (e) {
                const div = document.createElement('div');
                div.style.position = 'relative';

                const img = document.createElement('img');
                img.src = e.target.result;
                img.style.width = '100px';
                img.style.border = '1px solid #ccc';

                const btn = document.createElement('button');
                btn.innerText = '×';
                btn.style.position = 'absolute';
                btn.style.top = '0';
                btn.style.right = '0';
                btn.style.background = 'red';
                btn.style.color = 'white';
                btn.style.border = 'none';
                btn.style.cursor = 'pointer';

                btn.addEventListener('click', () => {
                    selectedNewFiles.splice(index, 1);
                    renderAllImages();
                });

                div.appendChild(img);
                div.appendChild(btn);
                previewContainer.appendChild(div);
            };

            reader.readAsDataURL(file);
        });
    }

    imageInput.addEventListener('change', function (e) {
        const files = Array.from(e.target.files);
        selectedNewFiles.push(...files);
        renderAllImages();
    });

    form.addEventListener('submit', function (e) {
        const dataTransfer = new DataTransfer();
        selectedNewFiles.forEach(file => dataTransfer.items.add(file));
        imageInput.files = dataTransfer.files; // override input files
    });

    // Initial render
    renderAllImages();
</script>
