@extends('layouts.app')
@section('title', 'E-Gazettes')
@section("scripts")
<script>
    $(document).ready(() => {
        const passkeyInputs = $('input[name="passkey"]');
        passkeyInputs.each(function(index, input) {
            const passkey = $(input).val();
            const obfuscatedPasskey = passkey.slice(0, 5) + '*'.repeat(passkey.length - 5); // Obfuscate the passkey
            var newElement = $('<span>' + obfuscatedPasskey + '</span>');
            // Add the new sibling element after the current input element
            $(input).after(newElement);
        });
        $("#key-copy").click(function() {
            const passkey = $(this).val();
            // Create a temporary textarea and append it to the body
            navigator.clipboard.writeText(passkey);
            alert('Key has been copied to clipboard');
        });
        $(".dload-btn").click(function() {
            $("#key-copy").val($(this).data("key"));
            $("#gazette-download").val($(this).data("link"));
        });
        $("#gazette-download").click(function() {
            const req = new XMLHttpRequest();
            const url = $(this).val();
            req.open("GET", url, true);
            req.responseType = "blob";

            req.onload = function(event) {
                var blob = req.response;
                var link = document.createElement('a');
                link.href = window.URL.createObjectURL(blob);
                link.download = "Dossier_" + new Date() + ".pdf";
                link.click();
            };

            req.send()
        });
    });
</script>
@stop
@section('content')
<div class="modal fade" id="passkeyModal" tabindex="-1" role="dialog" aria-labelledby="passkeyModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="passkeyModalLabel">Download instructions</h4>
            </div>
            <div class="modal-body">
                <p>Copy this passkey and proceed with the download.</p>
                <button class="btn btn-primary" id="key-copy">
                    <i class="fa fa-copy"></i> Copy passkey
                </button>
                <!-- Add any additional instructions here -->
                <button id="gazette-download" class="btn btn-primary">
                    <i class="fa fa-download"></i> Download
                </button>
            </div>
        </div>
    </div>
</div>
<section class="content-header">
    <h1 class="pull-left">E-Gazettes</h1>
    @can("create egazette")
    <div class="pull-right">
        <a href="{{ route('egazettes.create') }}" class="btn btn-primary">
            <i class="fa fa-plus"></i>
            Add New
        </a>
    </div>
    @endcan
</section>
<section class="content" style="margin-top: 20px;">
    <div class="clearfix"></div>
    <div class="clearfix"></div>
    <div class="row">
        <div class="col-md-12">
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title">E-Gazettes</h3>
                    <div class="box-tools">
                        <form action="{{ route('egazettes.index') }}" method="GET" class="form-inline">
                            <div class="input-group input-group-sm">
                                <input type="text" name="query" class="form-control" placeholder="Search...">
                                <span class="input-group-btn">
                                    <button type="submit" class="btn btn-default"><i class="fa fa-search"></i></button>
                                </span>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="box-body">
                    <div class="row">
                        @foreach($documents as $document)
                        <div class="col-md-3">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <h3 class="panel-title">Issue No: {{ $document->gaz_issue_no }}</h3>
                                </div>
                                <div class="panel-body">
                                    <p><strong>Sub Category:</strong> {{ $document->gaz_sub_category }}</p>
                                    <p><strong>Status:</strong> {{ $document->status }}</p>
                                    <p><strong>Published On:</strong> {{ $document->gaz_published_on }}</p>
                                    <a class="btn btn-warning" href="{{route('egazettes.show',['egazette'=>$document->id])}}">
                                        <i class="fa fa-eye"></i> Read More
                                    </a>
                                    <button class="btn btn-primary dload-btn" data-toggle="modal" data-target="#passkeyModal" data-key="{{$document->gaz_passkey}}" 
                                    data-link="{{route('egazettes.download',['id'=>$document->id])}}">
                                        <i class="fa fa-download"></i> Download
                                    </button>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>

                </div>
            </div>
        </div>
    </div>
</section>
@endsection