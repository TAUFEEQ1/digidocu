@extends('layouts.app')
@section('title', "Add Letter")
@section("css")
<style>
    .ocr-result {
        background-color: #e2e2e2;
        border: 1px dashed #a0a0a0;
        height: 282px;
        border-radius: 3px;
        overflow-y: auto;
    }
</style>
@stop
@section("scripts")
<script>
    $(document).ready(function() {
        // Initialize the wysihtml5 editor
        if(!window.env.production){
            return false;
        }
        const desc_lbl = $("label[for='description']").first();
        const ocrBadgeBtn = $('<button data-toggle="modal" data-target="#ocrModal" type="button">')
            .addClass("btn btn-primary badge")
            .text("OCR");
        // Append the OCR badge button after the description label
        desc_lbl.after(ocrBadgeBtn);

        $("#imageUploadInput").on("change", (e) => {
            // Get the selected file
            const file = e.target.files[0];

            if (file) {
                // Create a FileReader object
                const reader = new FileReader();

                // Set up the FileReader to read the selected file as a data URL
                reader.readAsDataURL(file);

                // When the FileReader finishes loading the file
                reader.onload = (event) => {
                    // Set the src attribute of the preview image to the data URL
                    $("#preview-img").attr("src", event.target.result);
                };
            }
        });
        $("#run-ocr").click(() => {
            // Get the input element
            const input = $("#imageUploadInput");

            // Check if the input has a file selected
            if (input[0].files.length > 0) {
                // Create a FormData object to send the file via AJAX
                const formData = new FormData();
                formData.append("file_scan", input[0].files[0]);

                // Send the file via AJAX
                $.ajax({
                    url: "/admin/letters/ocr",
                    type: "POST",
                    data: formData,
                    contentType: false,
                    processData: false,
                    headers: {
                        'X-CSRF-TOKEN': "{{csrf_token()}}"
                    },
                    success: function(response) {
                        // Handle success response
                        $(".ocr-result").text(response["text"]);
                        $("#cp-btn").prop("disabled", false);
                    },
                    error: function(xhr, status, error) {
                        // Handle error response
                        console.error("OCR failed:", error);
                    }
                });
            } else {
                // If no file is selected, display an error message or perform appropriate action
                alert("Please select an image before running OCR");
            }
        });
        $("#cp-btn").click(function() {
            // Get the content from the source element
            var contentToCopy = $(".ocr-result").text();

            // Use the native Clipboard API to copy the content to the clipboard
            navigator.clipboard.writeText(contentToCopy)
                .then(function() {
                    console.log('Text copied to clipboard');
                })
                .catch(function(err) {
                    console.error('Failed to copy text to clipboard:', err);
                });
        })
    });
</script>

@stop
@section('content')
<div class="modal fade" id="ocrModal" tabindex="-1" role="dialog" aria-labelledby="ocrModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="ocrModalLabel">OCR Modal</h4>
            </div>
            <div class="modal-body">
                <input type="file" id="imageUploadInput" accept="image/*" name="imageUploadInput" style="visibility:hidden;">
                <div class="row">
                    <div class="col-md-5">
                        <label for="imageUploadInput" class="thumbnail" id="thumbnail">
                            <img class="img-picker" id="preview-img" src="http://dummyimage.com/250x350/f5f5f5/000000&text=Click+to+upload+{{config('settings.file_label_plural')}}" alt="Pick a file" height="282" width="200" />
                    </div>
                    <div class="col-md-2">
                        <div style="margin-top:120px; text-align:center;">
                            <i class="fa fa-chevron-right"></i>
                        </div>
                    </div>
                    <div class="col-md-5">
                        <div class="ocr-result">

                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-info" disabled id="cp-btn">
                    <i class="fa fa-copy"></i>
                </button>
                <button type="button" class="btn btn-primary" id="run-ocr">Run OCR</button>
            </div>
        </div>
    </div>
</div>
<section class="content-header">
    <h1>
        Submit Letter
    </h1>
</section>
<div class="content">
    <div class="box box-primary">
        <div class="box-body">
            <div class="row">
                {!! Form::open(['route' => 'letters.store', 'files' => true]) !!} <!-- Add 'files' => true for file upload -->
                {!! Form::bsText('subject') !!}
                {!! Form::bsText('sender') !!}
                {!! Form::bsTextarea('description', null, ['class'=>'form-control b-wysihtml5-editor','id'=>"description"]) !!}
                {!! Form::bsText('sending_entity', null, ['placeholder' => 'Sending Entity']) !!} <!-- Add input field for sending_entity -->
                <div class="form-group">
                    <label for="file" class="col-md-3 control-label">Scanned PDF</label>
                    <div class="col-md-6">
                        {!! Form::file('file_scan', ['class'=>'form-control']) !!} <!-- File upload field -->
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-md-offset-8 col-md-4">
                        {!! Form::submit('Submit', ['class' => 'btn btn-primary pull-right']) !!}
                    </div>
                </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>
</div>

@endsection