<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buat Chirp</title>
    <!-- Tambahkan script TinyMCE -->
    <script src="https://cdn.tiny.cloud/1/no-api-key/tinymce/6/tinymce.min.js"></script>
    <script>
        tinymce.init({
            selector: '#content', // ID elemen textarea
            plugins: 'image media link',
            toolbar: 'undo redo | bold italic | alignleft aligncenter alignright | image media link',
            file_picker_callback: function(callback, value, meta) {
                if (meta.filetype == 'image') {
                    var input = document.createElement('input');
                    input.setAttribute('type', 'file');
                    input.setAttribute('accept', 'image/*');
                    input.onchange = function() {
                        var file = input.files[0];
                        var reader = new FileReader();
                        reader.onload = function(e) {
                            callback(e.target.result, { alt: file.name });
                        };
                        reader.readAsDataURL(file);
                    };
                    input.click();
                }
            }
        });
    </script>
</head>
<body>
    <form method="POST" action="{{ route('chirps.store') }}">
        @csrf
        <div>
            <label for="content">Konten Chirp:</label>
            <textarea id="content" name="content"></textarea>
        </div>
        <button type="submit">Kirim</button>
    </form>
</body>
</html>
