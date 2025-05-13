<h2>Create Post</h2>

<form action="{{ route('posts.store') }}" method="POST" enctype="multipart/form-data" id="createPostForm">
    @csrf

    <input type="text" name="title" placeholder="Title"><br><br>
    <textarea name="description" placeholder="Description"></textarea><br><br>
    <textarea name="code" placeholder="Code"></textarea><br><br>

    <input type="file" id="imageInput" name="images[]" multiple><br><br>

    {{-- ✅ Selected Image Preview --}}
    <div id="imagePreview" style="display: flex; gap: 10px; flex-wrap: wrap;"></div>

    <br>
    <button type="submit">Create</button>
</form>
<script>
    const imageInput = document.getElementById('imageInput');
    const previewContainer = document.getElementById('imagePreview');
    const createForm = document.getElementById('createPostForm');

    let selectedFiles = [];

    imageInput.addEventListener('change', function (e) {
        const files = Array.from(e.target.files);
        selectedFiles.push(...files);  // Add new files
        renderPreviews();
    });

    function renderPreviews() {
        previewContainer.innerHTML = '';

        selectedFiles.forEach((file, index) => {
            const reader = new FileReader();

            reader.onload = function (e) {
                const div = document.createElement('div');
                div.style.position = 'relative';

                const img = document.createElement('img');
                img.src = e.target.result;
                img.style.width = '100px';
                img.style.border = '1px solid #ccc';

                const removeBtn = document.createElement('button');
                removeBtn.innerText = '×';
                removeBtn.style.position = 'absolute';
                removeBtn.style.top = '0';
                removeBtn.style.right = '0';
                removeBtn.style.background = 'red';
                removeBtn.style.color = 'white';
                removeBtn.style.border = 'none';
                removeBtn.style.cursor = 'pointer';

                removeBtn.addEventListener('click', () => {
                    selectedFiles.splice(index, 1);
                    renderPreviews();
                });

                div.appendChild(img);
                div.appendChild(removeBtn);
                previewContainer.appendChild(div);
            };

            reader.readAsDataURL(file);
        });
    }

    createForm.addEventListener('submit', function (e) {
        const dataTransfer = new DataTransfer();
        selectedFiles.forEach(file => dataTransfer.items.add(file));
        imageInput.files = dataTransfer.files; // Replace actual input

        // Optional debug: console.log(selectedFiles)
    });
</script>
