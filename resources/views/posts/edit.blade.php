<h2>Edit Post</h2>

<form action="{{ route('posts.update', $post->id) }}" method="POST" enctype="multipart/form-data" id="editPostForm">
    @csrf
    @method('PUT')

    <input type="text" name="title" value="{{ $post->title }}"><br><br>
    <textarea name="description">{{ $post->description }}</textarea><br><br>

    <div id="codeContainer">
        @foreach ($post->codes ?? [] as $code)
            <div class="code-group">
                <textarea name="codes[]">{{ $code }}</textarea>
                <button type="button" class="remove-code-btn">Remove</button>
            </div>
        @endforeach
    </div>
    <button type="button" id="addCodeBtn">Add More Code</button><br><br>

    <input type="file" id="imageInput" name="images[]" multiple><br><br>
    <div id="imagePreview" style="display: flex; gap: 10px; flex-wrap: wrap;"></div>
    <div id="removedImagesContainer"></div>

    <button type="submit">Update</button>
</form>

<script>
    // Add More Code Field
    document.getElementById('addCodeBtn').addEventListener('click', () => {
        const div = document.createElement('div');
        div.className = 'code-group';
        div.innerHTML = `
            <textarea name="codes[]" placeholder="Another Code Block"></textarea>
            <button type="button" class="remove-code-btn">Remove</button>
        `;
        document.getElementById('codeContainer').appendChild(div);
    });

    // Remove Code Field
    document.getElementById('codeContainer').addEventListener('click', function (e) {
        if (e.target && e.target.classList.contains('remove-code-btn')) {
            e.target.parentElement.remove();
        }
    });

    // Image preview & removal
    const oldImages = @json($post->images ?? []);
    const previewContainer = document.getElementById('imagePreview');
    const removedImagesContainer = document.getElementById('removedImagesContainer');
    const imageInput = document.getElementById('imageInput');
    const form = document.getElementById('editPostForm');

    let selectedNewFiles = [];
    let existingImages = [...oldImages];

    function renderAllImages() {
        previewContainer.innerHTML = '';

        existingImages.forEach((imgPath, index) => {
            const div = document.createElement('div');
            div.style.position = 'relative';
            const img = document.createElement('img');
            img.src = `/storage/${imgPath}`;
            img.style.width = '100px';
            const btn = document.createElement('button');
            btn.innerText = '×';
            btn.style.position = 'absolute';
            btn.style.top = '0';
            btn.style.right = '0';
            btn.style.background = 'red';
            btn.style.color = 'white';
            btn.addEventListener('click', () => {
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

        selectedNewFiles.forEach((file, index) => {
            const reader = new FileReader();
            reader.onload = function (e) {
                const div = document.createElement('div');
                div.style.position = 'relative';
                const img = document.createElement('img');
                img.src = e.target.result;
                img.style.width = '100px';
                const btn = document.createElement('button');
                btn.innerText = '×';
                btn.style.position = 'absolute';
                btn.style.top = '0';
                btn.style.right = '0';
                btn.style.background = 'red';
                btn.style.color = 'white';
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

    form.addEventListener('submit', function () {
        const dataTransfer = new DataTransfer();
        selectedNewFiles.forEach(file => dataTransfer.items.add(file));
        imageInput.files = dataTransfer.files;
    });

    renderAllImages();
</script>
