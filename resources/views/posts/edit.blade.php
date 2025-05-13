<form action="{{ route('posts.update', $post->id) }}" method="POST" enctype="multipart/form-data" id="editPostForm">
    @csrf
    @method('PUT')

    <input type="text" name="title" value="{{ $post->title }}" placeholder="Title"><br><br>
    <textarea name="description" placeholder="Description">{{ $post->description }}</textarea><br><br>

    <!-- Code Blocks -->
    <div id="codeContainer">
        @foreach ($post->code_titles ?? [] as $index => $title)
            <div class="code-group" style="margin-bottom: 10px;">
                <input type="text" name="code_titles[]" value="{{ $title }}" placeholder="Code Title"><br>
                <textarea name="codes[]">{{ $post->codes[$index] ?? '' }}</textarea><br>
                <button type="button" class="remove-code-btn">Remove</button>
            </div>
        @endforeach
    </div>
    <button type="button" id="addCodeBtn">Add More Code</button><br><br>

    <!-- Image Input -->
    <input type="file" id="imageInput" name="images[]" multiple><br><br>

    <!-- New Images Section -->
    <div id="newImagesSection" style="margin-top: 20px;">
        <h4>Selected New Images:</h4>
        <div id="imagePreview" style="display: flex; gap: 10px; flex-wrap: wrap;"></div>
    </div>

    <!-- Old Images Section -->
    <div id="oldImagesSection" style="margin-top: 20px;">
        <h4>Old Images:</h4>
        <div id="oldImagePreview" style="display: flex; gap: 10px; flex-wrap: wrap;"></div>
    </div>

    <div id="removedImagesContainer"></div><br>

    <button type="submit">Update</button>
</form>





<!-- JavaScript -->
<script>

const codeContainer = document.getElementById('codeContainer');
const addCodeBtn = document.getElementById('addCodeBtn');

addCodeBtn.addEventListener('click', () => {
    const div = document.createElement('div');
    div.classList.add('code-group');
    div.style.marginBottom = '10px';
    div.innerHTML = `
        <input type="text" name="code_titles[]" placeholder="Code Title"><br>
        <textarea name="codes[]" placeholder="Another Code Block"></textarea><br>
        <button type="button" class="remove-code-btn">Remove</button>
    `;
    codeContainer.appendChild(div);
});

codeContainer.addEventListener('click', function (e) {
    if (e.target.classList.contains('remove-code-btn')) {
        e.target.parentElement.remove();
    }
});

const oldImages = @json($post->images ?? []);
const newImagePreview = document.getElementById('imagePreview');
const oldImagePreview = document.getElementById('oldImagePreview');
const imageInput = document.getElementById('imageInput');
const form = document.getElementById('editPostForm');

let selectedNewFiles = [];
let existingImages = [...oldImages];

function renderAllImages() {
    newImagePreview.innerHTML = '';
    oldImagePreview.innerHTML = '';

    // New Images section
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
            newImagePreview.appendChild(div);
        };
        reader.readAsDataURL(file);
    });

    // Old Images section
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
            document.getElementById('removedImagesContainer').appendChild(hidden);
            existingImages.splice(index, 1);
            renderAllImages();
        });
        div.appendChild(img);
        div.appendChild(btn);
        oldImagePreview.appendChild(div);
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
