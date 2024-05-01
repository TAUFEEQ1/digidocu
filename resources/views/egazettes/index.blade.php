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
        $(".key-copy").click(function() {
            const passkey = $(this).val();
            // Create a temporary textarea and append it to the body
            $('<textarea>').appendTo('body').val(passkey).select();
            // Copy the selected text to the clipboard
            document.execCommand('copy');
            // Remove the temporary textarea
            $('textarea').remove();
            alert('Key has been copied to clipboard');
        });
        $(".gazette-download").click(function(){
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
                    <h3 class="box-title">E-Gazette List</h3>
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
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Issue No</th>
                                <th>Sub Category</th>
                                <th>Status</th>
                                <th>Passkey</th>
                                <th>Published On</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($documents as $document)
                            <tr>
                                <td>{{ $document->gaz_issue_no }}</td>
                                <td>{{ $document->gaz_sub_category }}</td>
                                <td>{{ $document->status }}</td>
                                <td>
                                    @if ($document->gaz_passkey)
                                    <input type="hidden" name="passkey" value="{{ $document->gaz_passkey }}">
                                    <button class="btn btn-primary key-copy" value="{{ $document->gaz_passkey }}">
                                        <i class="fa fa-copy"></i>
                                    </button>
                                    @endif
                                </td>
                                <td>{{ $document->gaz_published_on }}</td>
                                <td>
                                    <button class="btn btn-primary gazette-download" value="{{route('egazettes.download',['id'=>$document->id])}}">
                                        <i class="fa fa-download"></i> Download
                                    </button>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection