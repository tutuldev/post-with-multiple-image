<h2>Create Post</h2>

<form action="{{ route('posts.store') }}" method="POST" enctype="multipart/form-data" id="createPostForm">
    @csrf

    <input type="text" name="title" placeholder="Title"><br><br>
    <textarea name="description" placeholder="Description"></textarea><br><br>

    <div id="codeContainer">
        <div class="code-group">
            <input type="text" name="code_titles[]" placeholder="Code Title">
            <textarea name="codes[]" placeholder="Code Block"></textarea>
            <button type="button" class="remove-code-btn">Remove</button>
        </div>
    </div>
    <button type="button" id="addCodeBtn">Add More Code</button><br><br>

    <input type="file" id="imageInput" name="images[]" multiple><br><br>
    <div id="imagePreview" style="display: flex; gap: 10px; flex-wrap: wrap;"></div>

    <button type="submit">Create</button>
</form>

<script>
    const codeContainer = document.getElementById('codeContainer');
    const addCodeBtn = document.getElementById('addCodeBtn');

    addCodeBtn.addEventListener('click', () => {
        const div = document.createElement('div');
        div.innerHTML = `
            <input type="text" name="code_titles[]" placeholder="Code Title"><br>
            <textarea name="codes[]" placeholder="Another Code Block"></textarea>
        `;
        codeContainer.appendChild(div);
    });

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
        const dataTransfer = new DataTransfer();

        selectedFiles.forEach((file, index) => {
            dataTransfer.items.add(file);
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

        imageInput.files = dataTransfer.files;
    }

    form.addEventListener('submit', function () {
        const dataTransfer = new DataTransfer();
        selectedFiles.forEach(file => dataTransfer.items.add(file));
        imageInput.files = dataTransfer.files;
    });
</script>


