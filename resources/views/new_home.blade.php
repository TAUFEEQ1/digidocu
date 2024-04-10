@extends('layouts.app')
@section('title', 'Home')
@section('content')
    <section class="content-header">
        <h1 class="pull-left">Dashboard</h1>
    </section>
    <section class="content" style="margin-top: 20px;">
        <div class="clearfix"></div>
        <div class="clearfix"></div>
        <div class="row">
            <div class="col-sm-12">
                <div class="box box-default">
                    <div class="box-header no-border">
                        <h3 class="box-title">Activity</h3>

                        <div class="box-tools pull-right">
                            {!! Form::open(['method' => 'get','style'=>'display:inline;']) !!}
                                {!! Form::hidden('activity_range', '', ['id' => 'activity_range']) !!}
                                <button type="button" id="activityrange" class="btn btn-default btn-sm">
                                    <i class="fa fa-calendar"></i>&nbsp;
                                    <span>Choose dates</span> <i class="fa fa-caret-down"></i>
                                </button>
                                {!! Form::button('<i class="fa fa-filter"></i>&nbsp;Filter', ['class' => 'btn btn-default btn-sm','type'=>'submit']) !!}
                            {!! Form::close() !!}
                            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i
                                    class="fa fa-minus"></i>
                            </button>
                            <button type="button" class="btn btn-box-tool" data-widget="remove"><i
                                    class="fa fa-times"></i></button>
                        </div>
                    </div>
                    <div class="box-body">
                        <ul class="timeline">
                            <li class="time-label">
                                <span class="bg-red">{{formatDate(optional($activities->first())->created_at,'d M Y')}}</span>
                            </li>
                            @foreach ($activities as $activity)
                                <li>
                                    <i class="fa fa-user bg-aqua" data-toggle="tooltip"
                                       title="{{$activity->createdBy->name}}"></i>

                                    <div class="timeline-item">
                                            <span class="time" data-toggle="tooltip"
                                                  title="{{formatDateTime($activity->created_at)}}"><i
                                                    class="fa fa-clock-o"></i> {{\Carbon\Carbon::parse($activity->created_at)->diffForHumans()}}</span>

                                        <h4 class="timeline-header no-border">{!! $activity->activity !!}</h4>
                                    </div>
                                </li>
                            @endforeach
                            <li>
                                <i class="fa fa-clock-o bg-gray"></i>
                            </li>
                        </ul>
                        <div class="text-center">
                            {!! $activities->appends(request()->all())->render() !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
