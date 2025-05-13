<h2>Create Post</h2>

<form action="{{ route('posts.store') }}" method="POST" enctype="multipart/form-data" id="createPostForm">
    @csrf

    <input type="text" name="title" placeholder="Title"><br><br>
    <textarea name="description" placeholder="Description"></textarea><br><br>

    <div id="codeContainer">
        <div class="code-group">
            <textarea name="codes[]" placeholder="Code Block 1"></textarea>
            <button type="button" class="remove-code-btn">Remove</button>
        </div>
    </div>
    <button type="button" id="addCodeBtn">Add More Code</button><br><br>

    <input type="file" id="imageInput" name="images[]" multiple><br><br>
    <div id="imagePreview" style="display: flex; gap: 10px; flex-wrap: wrap;"></div>

    <button type="submit">Create</button>
</form>

<script>
    // Add More Code Block
    document.getElementById('addCodeBtn').addEventListener('click', () => {
        const div = document.createElement('div');
        div.className = 'code-group';
        div.innerHTML = `
            <textarea name="codes[]" placeholder="Another Code Block"></textarea>
            <button type="button" class="remove-code-btn">Remove</button>
        `;
        document.getElementById('codeContainer').appendChild(div);
    });

    // Remove Code Block
    document.getElementById('codeContainer').addEventListener('click', function (e) {
        if (e.target && e.target.classList.contains('remove-code-btn')) {
            e.target.parentElement.remove();
        }
    });

    // Image Preview
    const imageInput = document.getElementById('imageInput');
    const previewContainer = document.getElementById('imagePreview');
    const form = document.getElementById('createPostForm');
    let selectedFiles = [];

    imageInput.addEventListener('change', function (e) {
        const files = Array.from(e.target.files);
        selectedFiles.push(...files);
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
                const removeBtn = document.createElement('button');
                removeBtn.innerText = '×';
                removeBtn.style.position = 'absolute';
                removeBtn.style.top = '0';
                removeBtn.style.right = '0';
                removeBtn.style.background = 'red';
                removeBtn.style.color = 'white';
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

    form.addEventListener('submit', function () {
        const dataTransfer = new DataTransfer();
        selectedFiles.forEach(file => dataTransfer.items.add(file));
        imageInput.files = dataTransfer.files;
    });
</script>
