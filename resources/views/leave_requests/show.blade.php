@extends('layouts.app')
@section('title',"Show Leave Request")
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
@section('content')
<section class="content-header" style="margin-bottom: 27px;">
    <h1 class="pull-left">
        Leave Request
        <small> by {{$document->createdBy->name}}</small>
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
                        <label>Leave Type:</label>
                        <span>{{ $document->lv_type }}</span>
                    </div>

                    <div class="form-group">
                        <label>Status:</label>
                        @if ($document->status==config('constants.LEAVE_RQ_STATES.MG_DIR_APPROVED'))
                        <span class="label label-success">{{$document->status}}</span>
                        @elseif($document->status==config('constants.LEAVE_RQ_STATES.LN_MGR_APPROVED'))
                        <span class="label label-info">{{$document->status}}</span>
                        @elseif(in_array($document->status,[config('constants.LEAVE_RQ_STATES.LN_MGR_DENIED'),config('constants.LEAVE_RQ_STATES.MG_DIR_DENIED')]))
                        <span class="label label-danger">{{$document->status}}</span>
                        @else
                        <span class="label label-warning">{{$document->status}}</span>
                        @endif
                    </div>
                    <div class="form-group">
                        <label>Line Manager:</label>
                        <span>{{ $document->lineManager->name }}</span>
                    </div>
                    <div class="form-group">
                        <label>Created By:</label> {{$document->createdBy->name}}
                    </div>
                    <div class="form-group">
                        <label>Designation:</label>
                        <span>{{ $document->lv_designation }}</span>
                    </div>
                    <div class="form-group">
                        <label>Deparment:</label>
                        <span>{{ $document->lv_department }}</span>
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
                    <li class="active"><a href="#tab_activity" data-toggle="tab" aria-expanded="false">Activity</a></li>
                    @can("line_manage_leave_requests",$user)
                    <li><a href="#tab_line_management" data-toggle="tab" aria-expanded="false">Line Management</a></li>
                    @endcan
                    @if($document->status == config('constants.LEAVE_RQ_STATES.LN_MGR_APPROVED') || $document->status == config("constants.LEAVE_RQ_STATES.LN_MGR_DENIED"))
                    <li><a href="#tab_line_manager_details" data-toggle="tab" aria-expanded="false">Line Manager Details</a></li>
                    @endif
                    @can("hr_manage_leave_requests",$user)
                    <li><a href="#tab_hr_management" data-toggle="tab" aria-expanded="false">HR Management</a></li>
                    @endcan
                    @if($document->status == config('constants.LEAVE_RQ_STATES.HR_MGR_APPROVED') || $document->status == config("constants.LEAVE_RQ_STATES.HR_MGR_DENIED"))
                    <li><a href="#tab_hr_manager_details" data-toggle="tab" aria-expanded="false">HR Manager Details</a></li>
                    @endcan
                </ul>
                <div class="tab-content">
                    <div class="tab-pane active" id="tab_activity">
                        <ul class="timeline">
                            <li class="time-label">
                                <span class="bg-red">{{formatDate($document->updated_at,'d M Y')}}</span>
                            </li>
                            @foreach ($document->activities()->orderBy('created_at', 'desc')->paginate(10) as $activity)
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
                    @can("line_manage_leave_requests",$user)
                    <div class="tab-pane" id="tab_line_management">
                        @if ($document->status==config('constants.LEAVE_RQ_STATES.SUBMITTED'))
                        {!! Form::open(['route' => ['leave_requests.review', $document->id], 'method' => 'post']) !!}
                        <div class="form-group text-center">
                            <textarea class="form-control" name="vcomment" id="vcomment" rows="4" placeholder="Enter Comment to verify with comment(optional)"></textarea>
                        </div>
                        <div class="form-group text-center">
                            <button class="btn btn-success" type="submit" name="action" value="{{ config('constants.LEAVE_RQ_STATES.LN_MGR_APPROVED') }}"><i class="fa fa-check"></i> Approve
                            </button>
                            <button class="btn btn-danger" type="submit" name="action" value="{{ config('constants.LEAVE_RQ_STATES.LN_MGR_DENIED') }}"><i class="fa fa-close"></i> Reject
                            </button>
                        </div>
                        {!! Form::close() !!}
                        @else
                        <div class="form-group">
                            <span class="label label-success">Line Management</span>
                        </div>
                        <div class="form-group">
                            Line Manager: <b>{{$document->lineManager->name}}</b>
                        </div>
                        <div class="form-group">
                            Line Manager Comments: <b>{{ $document->lv_line_manager_notes }}</b>
                        </div>
                        <div class="form-group">
                            Line Managed At: <b>{{formatDateTime($document->lv_line_managed_at)}}</b>
                            ({{\Carbon\Carbon::parse($document->lv_line_managed_at)->diffForHumans()}})
                        </div>
                        @endif
                    </div>
                    @endcan
                    @if($document->status == config('constants.LEAVE_RQ_STATES.LN_MGR_APPROVED') || $document->status == config("constants.LEAVE_RQ_STATES.LN_MGR_DENIED"))
                    <div class="tab-pane" id="tab_line_manager_details">
                        <div class="form-group">
                            Line Manager: <b>{{ $document->lineManager->name }}</b>
                        </div>
                        <div class="form-group">
                            Line Manager Notes: <p>{{ $document->lv_line_manager_notes }}</p>
                        </div>
                        <div class="form-group">
                            Line Managed At: <b>{!! formatDateTime($document->lv_line_managed_at) !!}</b>
                            ({{\Carbon\Carbon::parse($document->lv_line_managed_at)->diffForHumans()}})
                        </div>
                    </div>
                    @endif
                    @can("hr_manage_leave_requests",$user)
                    <div class="tab-pane" id="tab_hr_management">
                        @if ($document->status==config('constants.LEAVE_RQ_STATES.LN_MGR_APPROVED'))
                        {!! Form::open(['route' => ['leave_requests.review', $document->id], 'method' => 'post']) !!}
                        <div class="form-group text-center">
                            <textarea class="form-control" name="vcomment" id="vcomment" rows="4" placeholder="Enter Comment to verify with comment(optional)"></textarea>
                        </div>
                        <div class="form-group text-center">
                            <button class="btn btn-success" type="submit" name="action" value="{{ config('constants.LEAVE_RQ_STATES.HR_MGR_APPROVED') }}"><i class="fa fa-check"></i> Approve
                            </button>
                            <button class="btn btn-danger" type="submit" name="action" value="{{ config('constants.LEAVE_RQ_STATES.HR_MGR_DENIED') }}"><i class="fa fa-close"></i> Reject
                            </button>
                        </div>
                        {!! Form::close() !!}
                        @else
                        <div class="form-group">
                            <span class="label label-success">HR Management</span>
                        </div>
                        <div class="form-group">
                            HR Manager: <b>{{$document->hrManager->name}}</b>
                        </div>
                        <div class="form-group">
                            HR Manager Comments: <b>{{ $document->lv_hr_manager_notes }}</b>
                        </div>
                        <div class="form-group">
                            HR Managed At: <b>{{formatDateTime($document->lv_hr_managed_at)}}</b>
                            ({{\Carbon\Carbon::parse($document->lv_hr_managed_at)->diffForHumans()}})
                        </div>
                        @endif
                    </div>
                    @endcan
                    @if($document->status == config('constants.LEAVE_RQ_STATES.HR_MGR_APPROVED') || $document->status == config("constants.LEAVE_RQ_STATES.HR_MGR_DENIED"))
                    <div class="tab-pane" id="tab_hr_manager_details">
                        <div class="form-group">
                            <span class="label label-success">HR Management</span>
                        </div>
                        <div class="form-group">
                            HR Manager: <b>{{$document->hrManager->name}}</b>
                        </div>
                        <div class="form-group">
                            HR Manager Comments: <b>{{ $document->lv_hr_manager_notes }}</b>
                        </div>
                        <div class="form-group">
                            HR Managed At: <b>{{formatDateTime($document->lv_hr_managed_at)}}</b>
                            ({{\Carbon\Carbon::parse($document->lv_hr_managed_at)->diffForHumans()}})
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection