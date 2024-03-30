@extends('layouts.app')
@section('title',"Show Letter")
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
@stop
@section('scripts')
<script src="https://cdn.scaleflex.it/plugins/filerobot-image-editor/3/filerobot-image-editor.min.js"></script>
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
        Letter
        <small>{{$document->subject}}</small>
    </h1>
    <h1 class="pull-right" style="margin-bottom: 5px;">
        <div class="dropdown" style="display: inline-block">
            <button class="btn btn-primary dropdown-toggle" type="button" data-toggle="dropdown"><i class="fa fa-download"></i> Download Zip
                <span class="caret"></span></button>
            <ul class="dropdown-menu">
                <li>
                    <a href="{{route('files.downloadZip',['dir'=>'all','id'=>$document->id])}}">All</a>
                </li>
                <li>
                    <a href="{{route('files.downloadZip',['dir'=>'original','id'=>$document->id])}}">Original</a>
                </li>
            </ul>
        </div>
        @can('edit', $document)
        <a href="{{route('documents.edit', $document->id)}}" class="btn btn-primary"><i class="fa fa-edit"></i>
            Edit</a>
        @endcan
        @can('delete', $document)
        {!! Form::open(['route' => ['documents.destroy', $document->id], 'method' => 'delete', 'style'=>'display:inline;']) !!}
        <button class="btn btn-danger" onclick="conformDel(this,event)" type="submit"><i class="fa fa-trash"></i>
            Delete
        </button>
        {!! Form::close() !!}
        @endcan
    </h1>
</section>
<div class="content">
    <div class="clearfix"></div>
    <div class="clearfix"></div>
    <div class="row">
        <div class="col-sm-3">
            <div class="box box-primary">
                <div class="box-body">
                    <div class="form-group">
                        <label>Letter Subject:</label>
                        <p>{{ $document->subject }}</p>
                    </div>
                    <div class="form-group">
                        <label> Letter Sender:</label>
                        <p>{{ $document->sender }}</p>
                    </div>
                    <div class="form-group">
                        <label>Description:</label>
                        <p>{!! $document->description !!}</p>
                    </div>
                    <div class="form-group">
                        <label>Status:</label>
                        @if ($document->status==config('constants.LETTER_STATES.ASSIGNED'))
                        <span class="label label-success">{{$document->status}}</span>
                        @elseif($document->status==config('constants.LETTER_STATES.MANAGED'))
                        <span class="label label-info">{{$document->status}}</span>
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
                    @can('execute_letters', $user)
                    <li class=""><a href="#tab_execution" data-toggle="tab" aria-expanded="false">Execution</a></li>
                    @endcan
                    <li class=""><a href="#tab_activity" data-toggle="tab" aria-expanded="false">Activity</a></li>
                </ul>
                <div class="tab-content">
                    <div class="tab-pane active" id="tab_files">
                        <div class="row">
                            @foreach ($document->files->sortBy('file_type_id') as $file)
                            <div class="col-md-4">
                                <div class="box custom-box">
                                    <div class="box-body">
                                        <img onclick="showFileModal({{json_encode($file)}})" style="cursor:pointer;" src="{{buildPreviewUrl($file->file)}}" alt="">
                                    </div>
                                    <div class="box-header">
                                        <div class="user-block">
                                            <span class="label label-default">{{$file->fileType->name}}</span>
                                            <span class="username" style="cursor:pointer;" onclick="showFileModal({{json_encode($file)}})">{{$file->name}}</span>
                                            <small class="description text-gray"><b title="{{formatDateTime($file->created_at)}}" data-toggle="tooltip">{{\Carbon\Carbon::parse($file->created_at)->diffForHumans()}}</b>
                                                by <b>{{$file->createdBy->name}}</b></small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @can('execute_letters', $user)
                    <div class="tab-pane" id="tab_execution">
                        @if ($document->status==config('constants.LETTER_STATES.SUBMITTED'))
                        {!! Form::open(['route' => ['letters.execute', $document->id], 'method' => 'post']) !!}
                        <div class="form-group text-center">
                            <textarea class="form-control" name="vcomment" id="vcomment" rows="4" placeholder="Enter Comment to verify with comment(optional)"></textarea>
                        </div>
                        <div class="form-group text-center">
                            <button class="btn btn-success" type="submit" name="action" value="approve"><i class="fa fa-check"></i> Approve
                            </button>
                            <button class="btn btn-danger" type="submit" name="action" value="reject"><i class="fa fa-close"></i> Reject
                            </button>
                        </div>
                        {!! Form::close() !!}
                        @else
                        <div class="form-group">
                            <span class="label label-success">Execution</span>
                        </div>
                        <div class="form-group">
                            Executive Secretary: <b>{{$document->executedBy->name}}</b>
                        </div>
                        <div class="form-gorup">
                            Executed At: <b>{{formatDateTime($document->executed_at)}}</b>
                            ({{\Carbon\Carbon::parse($document->executed_at)->diffForHumans()}})
                        </div>
                        @endif
                    </div>
                    @endcan
                    <div class="tab-pane" id="tab_activity">
                        <ul class="timeline">
                            <li class="time-label">
                                <span class="bg-red">{{formatDate($document->updated_at,'d M Y')}}</span>
                            </li>
                            @foreach ($document->activities as $activity)
                            <li>
                                <i class="fa fa-user bg-aqua" data-toggle="tooltip" title="{{$activity->createdBy->name}}"></i>

                                <div class="timeline-item">
                                    <span class="time" data-toggle="tooltip" title="{{formatDateTime($activity->created_at)}}"><i class="fa fa-clock-o"></i> {{\Carbon\Carbon::parse($activity->created_at)->diffForHumans()}}</span>

                                    <h4 class="timeline-header no-border">{!! $activity->activity !!}</h4>
                                </div>
                            </li>
                            @endforeach
                            <li>
                                <i class="fa fa-clock-o bg-gray"></i>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div id="sticky_footer">
    <form id="frm_image2pdf" action="{{route('files.downloadPdf')}}" method="post" style="display: inline">
        @csrf
        <input type="hidden" name="images">
        <input type="hidden" name="images_varient">
        <div class="dropup">
            <button class="btn btn-success dropdown-toggle" type="button" data-toggle="dropdown"><i class="fa fa-file-pdf-o"></i> Convert PDF
                <span class="caret"></span></button>
            <ul class="dropdown-menu">
                <li><a href="javascript:void(0);" onclick="submitPdfForm('original')">Original</a></li>
                @foreach (explode(',',config('settings.image_files_resize')) as $varient)
                <li><a href="javascript:void(0);" onclick="submitPdfForm('{{$varient}}')">{{$varient}}w</a></li>
                @endforeach
            </ul>
        </div>
    </form>
</div>
@endsection