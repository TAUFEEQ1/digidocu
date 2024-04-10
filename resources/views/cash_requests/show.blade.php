@extends('layouts.app')
@section('title',"Show Cash Request")
@section('css')
<style>
    .box.custom-box {
        border: 1px solid #3c8dbc;
        box-shadow: 0 1px 2px 1px rgba(0, 0, 0, 0.08)
    }

    .box.custom-box .box-header {
        background-color: #3c8dbc;
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
</script>
@stop
@section('content')
<div id="modal-space">
</div>
<section class="content-header" style="margin-bottom: 27px;">
    <h1 class="pull-left">
        Cash Request
        <small>{{$document->cr_title}}</small>
    </h1>
    <div class="content">
        <div class="clearfix"></div>
        <div class="clearfix"></div>
        <div class="row">
            <div class="col-sm-3">
                <div class="box box-primary">
                    <div class="box-body">
                        <div class="form-group">
                            <label>Title:</label>
                            <p>{{ $document->cr_title }}</p>
                        </div>
                        <div class="form-group">
                            <label> Purpose:</label>
                            <p>{!! $document->cr_purpose !!}</p>
                        </div>
                        <div class="form-group">
                            <label>Amount (UGX):</label>
                            <p>{!! number_format($document->cr_amount, 0, '.', ',') !!}</p>
                        </div>
                        <div class="form-group">
                            <label>Amount In Words:</label>
                            <p>{{ $document->cr_amount_words }} shillings</p>
                        </div>
                        <div class="form-group">
                            <label>Status:</label>
                            @if ($document->status==config('constants.CASH_RQ_STATES.MG_DIR_APPROVED'))
                            <span class="label label-success">{{$document->status}}</span>
                            @elseif($document->status==config('constants.CASH_RQ_STATES.AUDITOR_APPROVED'))
                            <span class="label label-info">{{$document->status}}</span>
                            @elseif($document->status==config('constants.CASH_RQ_STATES.FINANCE_APPROVED'))
                            <span class="label label-danger">{{$document->status}}</span>
                            @else
                            <span class="label label-warning">{{$document->status}}</span>
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
            <div class="col-sm-9">
                <div class="nav-tabs-custom">
                    <ul class="nav nav-tabs">
                        <li class="active"><a href="#tab_files" data-toggle="tab" aria-expanded="true">{{ucfirst(config('settings.file_label_plural'))}}</a>
                        </li>
                        @can('hod_review_cr',$user)
                        <li class=""><a href="#tab_hod_review" data-toggle="tab" aria-expanded="false">HoD Review</a></li>
                        @endcan
                        @if ($document->cr_hod_id)
                        <li class=""><a href="#tab_hod_remarks" data-toggle="tab" aria-expanded="false">HoD Remarks</a></li>
                        @endif
                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane active" id="tab_files">
                            <div class="row">
                                @foreach ($document->files->sortBy('file_type_id') as $file)
                                @php
                                $encodedFile = json_encode($file);
                                @endphp
                                <div class="col-md-4">
                                    <div class="box custom-box">
                                        <div class="box-body">
                                            <img onclick="showFileModal({{$encodedFile}})" style="cursor:pointer;" src="{{buildPreviewUrl($file->file)}}" alt="">
                                        </div>
                                        <div class="box-header">
                                            <div class="user-block">
                                                <span class="label label-default">{{$file->fileType->name}}</span>
                                                <span class="username" style="cursor:pointer;" onclick="showFileModal({{$encodedFile}})">{{$file->name}}</span>
                                                <small class="description text-gray"><b title="{{formatDateTime($file->created_at)}}" data-toggle="tooltip">{{\Carbon\Carbon::parse($file->created_at)->diffForHumans()}}</b>
                                                    by <b>{{$file->createdBy->name}}</b></small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endforeach

                            </div>
                        </div>
                        @can("hod_review_cr",$user)
                        <div class="tab-pane" id="tab_hod_review">
                            @if ($document->status==config('constants.CASH_RQ_STATES.SUBMITTED'))
                            {!! Form::open(['route' => ['cash_requests.review', $document->id], 'method' => 'post']) !!}
                            <div class="form-group text-center">
                                <textarea class="form-control" name="vcomment" id="vcomment" rows="4" placeholder="Enter Comment to verify with comment(optional)"></textarea>
                            </div>
                            <div class="form-group text-center">
                                <button class="btn btn-success" type="submit" name="action" value="{{ config('constants.CASH_RQ_STATES.HOD_APPROVED') }}"><i class="fa fa-check"></i> Approve
                                </button>
                                <button class="btn btn-danger" type="submit" name="action" value="{{ config('constants.CASH_RQ_STATES.HOD_DENIED') }}"><i class="fa fa-close"></i> Reject
                                </button>
                            </div>
                            {!! Form::close() !!}
                            @else
                            <div class="form-group">
                                <span class="label label-success">HoD Reviewal</span>
                            </div>
                            <div class="form-group">
                                HoD: <b>{{$document->hod->name}}</b>
                            </div>
                            <div class="form-group">
                                HoD Remarks: <b>{{$document->cr_hod_notes}}</b>
                            </div>
                            <div class="form-group">
                                Reviewed At: <b>{{formatDateTime($document->cr_hod_at)}}</b>
                                ({{\Carbon\Carbon::parse($document->cr_hod_at)->diffForHumans()}})
                            </div>
                            @endif
                        </div>
                        @endcan
                        @if($document->cr_hod_id)
                        <div class="tab-pane" id="tab_hod_remarks">
                            <div class="form-group">
                                <span class="label label-success">HoD Remarks</span>
                            </div>
                            <div class="form-group">
                                HoD: <b>{{$document->hod->name}}</b>
                            </div>
                            <div class="form-group">
                                HoD Remarks: <b>{{$document->cr_hod_notes}}</b>
                            </div>
                            <div class="form-group">
                                Reviewed At: <b>{{formatDateTime($document->cr_hod_at)}}</b>
                                ({{\Carbon\Carbon::parse($document->cr_hod_at)->diffForHumans()}})
                            </div>
                        </div>
                        @endif

                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection