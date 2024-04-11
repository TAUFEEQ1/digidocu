@extends('layouts.app')
@section('title', "Image2PDF")
@section("css")
<style>
    .thumbnail {
        width: 250px;
        height: 300px;
        border: 2px solid #ddd;
        cursor: pointer;
        margin-left: 4.5%;
    }

    .thumbnail img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    #fileInput {
        display: none;
    }

    .img-grid {
        display: grid;
        grid-column-gap: 10px;
        grid-template-columns: 1fr 1fr 1fr 1fr;
        border: 1px dashed silver;
        padding-inline: 10px 10px;
        padding-block: 10px 10px;
        height: 540px;
        grid-row-gap: 10px;
        overflow-y: auto;
    }

    .img-grid-item {
        width: 200px;
        height: 280px;
        border: 2px solid #ddd;

    }
</style>
@stop
@section('content')
<section class="content-header">
    <h1>
        Convert Images to PDF
    </h1>
</section>
<section class="content">
    <div class="box box-primary">
        <div class="box-body">
            <div class="row" style="padding-block: 20px; padding-inline:20px;">
                <div class="col-md-3">
                    <label for="fileInput" class="thumbnail" id="thumbnail">
                        <img class="img-picker" src="http://dummyimage.com/250x300/f5f5f5/000000&text=Click+to+upload+{{config('settings.file_label_plural')}}" alt="Pick a file" height="120" />
                    </label>
                    <input type="file" id="fileInput" accept="image/*" multiple>
                </div>
                <div class="col-md-9">
                    <div class="img-grid" id="preview">

                    </div>
                    <div class="btn btn-info" style="margin-top: 10px;" id="download-pdf">Download PDF</div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
@section("scripts")
<script src="
https://cdn.jsdelivr.net/npm/sortablejs@1.15.2/Sortable.min.js
"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/axios/1.6.8/axios.min.js" integrity="sha512-PJa3oQSLWRB7wHZ7GQ/g+qyv6r4mbuhmiDb8BjSFZ8NZ2a42oTtAq5n0ucWAwcQDlikAtkub+tPVCw4np27WCg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script>
    const fileInput = document.getElementById('fileInput');
    const thumbnail = document.getElementById('thumbnail');
    const preview = document.getElementById('preview');
    const downloadBtn = document.getElementById('download-pdf');
    var sortable = Sortable.create(preview);
    const uploaded = [];
    fileInput.addEventListener('change', () => {
        // preview.innerHTML = "";
        const files = fileInput.files;
        if (files.length > 0) {
            for (let i = 0; i < files.length; i++) {
                const reader = new FileReader();
                reader.onload = () => {
                    const img = document.createElement('img');
                    img.src = reader.result;
                    img.classList.add("img-grid-item");
                    const fileName = files[i].name;
                    img.dataset.fileName = fileName;
                    preview.appendChild(img);
                    uploaded.push(files[i]);
                };
                reader.readAsDataURL(files[i]);
            }
        }

    });
    downloadBtn.addEventListener('click', () => {
        // get children of element called preview, get the child.dataset.name
        // Get all children elements of 'preview' and convert it into an array using Array.from
        const children = Array.from(preview.children);

        // Use map to extract the dataset.name property from each child element
        const names = children.map(child => child.dataset.fileName);

        // Create a new array to hold the reordered File elements
        const reorderedUploaded = [];

        // Reorder the "uploaded" items based on the order specified in "names"
        names.forEach((name, index) => {
            // Find the File element in "uploaded" with a name matching the current name
            const file = uploaded.find(file => file.name === name);

            // If the file is found, push it into the reorderedUploaded array
            if (file) {
                reorderedUploaded.push(file);
            }
        });
        const formData = new FormData();
        reorderedUploaded.forEach(file => {
            formData.append('files[]', file);
        });
        // Include CSRF token in headers
        const headers = {
            'X-CSRF-TOKEN': "{{csrf_token()}}"
        };

        // Make a POST request using Axios
        axios.post('/admin/plugins/image2pdf/store', formData, {
                headers
            })
            .then(response => {
                window.open(response.data["pdf_url"],"_blank");
          
            })
            .catch(error => {
                // Handle error
                console.error(error);
            });
    });
</script>
@stop