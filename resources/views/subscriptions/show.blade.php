@extends('layouts.app')
@section('title',"Show Advert")
@section('css')
<style>
    .box.custom-box {
        border: 1px solid black;
        box-shadow: 0 1px 2px 1px rgba(0, 0, 0, 0.08)
    }

    .box.custom-box .box-header {
        background-color: black;
        color: #fff;
        padding: 3px 5px;
    }

    .custom-box .user-block>.username,
    .custom-box .user-block>.description {
        margin-left: 0;
    }

    .custom-box .box-body img {
        height: 145px;
        object-fit: contain;
        width: 100%;
        border-radius: 3px;
    }

    object.obj-file-box {
        height: 80vh;
        object-fit: contain;
        width: 100%;
        border: 1px solid rgba(0, 40, 100, 0.2);
        box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
    }

    .img-d-select .icheckbox_square-blue {
        position: absolute;
        right: 0;
        top: 0;
    }

    #sticky_footer {
        position: fixed;
        bottom: -4px;
        right: 10px;
    }
</style>
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
<style>
    .file-drop-area {
        position: relative;
        display: flex;
        align-items: center;
        width: 450px;
        max-width: 100%;
        padding: 25px;
        border: 1px dashed rgba(0, 0, 0, 0.4);
        border-radius: 3px;
        transition: 0.2s;
        background-color: #e2e2e2;
    }

    .choose-file-button {
        flex-shrink: 0;
        background-color: rgba(255, 255, 255, 0.04);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 3px;
        padding: 8px 15px;
        margin-right: 10px;
        font-size: 12px;
        text-transform: uppercase;
    }

    .file-message {
        font-size: small;
        font-weight: 300;
        line-height: 1.4;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .file-input {
        position: absolute;
        left: 0;
        top: 0;
        height: 100%;
        width: 100%;
        cursor: pointer;
        opacity: 0;

    }
</style>
@stop
@section('scripts')
<script src="https://cdn.scaleflex.it/plugins/filerobot-image-editor/3/filerobot-image-editor.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
<script id="file-modal-template" type="text/x-handlebars-template">
    <div id="fileModal" class="modal fade" role="dialog">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">@{{name}}</h4>
                    </div>
                    <div class="modal-body">
                        <div class="col-md-3">
                            <div class="form-group">
                                <a href="{{\Illuminate\Support\Str::finish(route('files.showfile',['dir'=>'original']),"/")}}@{{file}}?force=true"
                                   download class="btn btn-primary"><i
                                        class="fa fa-download"></i> Download original
                                </a>
                            </div>
                            <div class="form-group">
                                <label>{{ucfirst(config('settings.file_label_singular'))." Type"}}</label>
                                <p>@{{file_type.name}}</p>
                            </div>
                            <div class="form-group">
                                <label>Uploaded By:</label>
                                <p>
                                    @{{created_by.name}}
                                </p>
                            </div>
                            <div class="form-group">
                                <label>Uploaded On:</label>
                                <p>@{{formatDate created_at}}</p>
                            </div>
                            @{{#each custom_fields}}
                            <div class="form-group">
                                <label>@{{titleize @key}}</label>
                                <p>@{{this}}</p>
                            </div>
                            @{{/each}}
                        </div>
                        <div class="col-md-9">
                            <div class="file-modal-preview">
                                <object class="obj-file-box" classid=""
                                        data="{{\Illuminate\Support\Str::finish(route('files.showfile',['dir'=>'original']),"/")}}@{{file}}">
                                </object>
                            </div>
                        </div>
                    </div>
                    <div class="clearfix"></div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-close"></i>
                            Close
                        </button>
                    </div>
                </div>

            </div>
        </div>
    </script>
<script>
    const ImageEditor = new FilerobotImageEditor();


    function showFileModal(data) {
        var template = Handlebars.compile($("#file-modal-template").html());
        var html = template(data);
        $("#modal-space").html(html);
        $("#fileModal").modal('show');

    }

    function submitPdfForm(varient) {
        $("input[name='images_varient']").val(varient);
        $("#frm_image2pdf").submit();
    }

    $(function() {
        $("input[name='topdf_check[]']").on('ifToggled', function(event) {
            var selectedValues = $("input[name='topdf_check[]']:checked").map(function() {
                return $(this).val();
            }).toArray();
            if (selectedValues.length > 0) {
                $("#sticky_footer").show();
            } else {
                $("#sticky_footer").hide();
            }
            $("input[name='images']").val(selectedValues.join());
        });
        $("input[name='topdf_check[]']").trigger('ifToggled');
    });

    $(".show-file-modal").on('click', function() {
        showFileModal($(this).data("encodedfile"));
    })
</script>
@stop
@section('content')
<div id="modal-space">
</div>
<section class="content-header" style="margin-bottom: 27px;">
    <h1 class="pull-left">
        Subscription
    </h1>
    <div class="content">
        <div class="clearfix"></div>
        <div class="clearfix"></div>
        <div class="row">
            <div class="col-sm-3">
                <div class="box box-primary">
                    <div class="box-body">
                        <div class="form-group">
                            <label>Period:</label>
                            <p>{{ formatDateTime($document->sub_start_date) }} - {{ formatDateTime($document->sub_end_date) }}</p>
                        </div>
                        <div class="form-group">
                            <label> Category:</label>
                            <p>{!! $document->sub_type !!}</p>
                        </div>
                        <div class="form-group">
                            <label>Amount (UGX):</label>
                            <p>{!! number_format($document->sub_amount, 0, '.', ',') !!}</p>
                        </div>
                        <div class="form-group">
                            <label>Status:</label>
                            @if ($document->status==config('constants.SUB_STATUSES.ACTIVE'))
                            <span class="label label-success">{{$document->status}}</span>
                            @elseif($document->status==config('constants.SUB_STATUSES.PENDING PAYMENT'))
                            <span class="label label-info">{{$document->status}}</span>
                            @elseif($document->status==config("constants.SUB_PAY_STATES.FAILED"))
                            <span class="label label-danger">{{$document->status}}</span>
                            @else
                            <span class="label label-warning">{{ $document->status }}</span>
                            @endif
                        </div>
                        <div class="form-group">
                            <label>Created By:</label> {{$document->createdBy->name}}
                        </div>
                        <div class="form-group">
                            <label>Created At:</label>
                            <p>{!! formatDateTime($document->created_at) !!} <br>
                                ({{\Carbon\Carbon::parse($document->created_at)->diffForHumans()}})
                            </p>
                        </div>
                        <div class="form-group">
                            <label>Last Updated:</label>
                            <p>{!! formatDateTime($document->updated_at) !!} <br>
                                ({{\Carbon\Carbon::parse($document->updated_at)->diffForHumans()}})
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection