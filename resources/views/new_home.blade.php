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
            <div class="col-md-12">
                <div class="box">
                    <div class="box-header with-border mt-2">
                        <h3 class="box-title">Document List</h3>
                        <div class="box-tools">
                            <form action="{{ route('documents.search') }}" method="GET" class="form-inline">
                                <div class="input-group input-group-sm">
                                    <div class="row">
                                        <div class="col-md-5">
                                            <select name="category" class="form-control">
                                                <option value="">All Categories</option>
                                                <option value="Letters">Letters</option>
                                                <option value="Leave Requests">Leave Requests</option>
                                            </select>
                                        </div>
                                        <div class="col-md-5">
                                            <input type="text" name="query" class="form-control" placeholder="Search...">
                                        </div>
                                        <div class="col-md-1">
                                            <span class="input-group-btn">
                                                <button type="submit" class="btn btn-default"><i class="fa fa-search"></i></button>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="box-body">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Category</th>
                                    <th>Status</th>
                                    <th>Created At</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($documents as $document)
                                <tr>
                                    <td>{{ ucfirst(strtolower($document->category));}}</td>
                                    <td>{{ $document->status }}</td>
                                    <td>{{ $document->created_at }}</td>
                                    <td>
                                        @if ($document->category == config("constants.DOC_TYPES.LETTER"))
                                            <a href="{{ route('letters.show', $document->id) }}" class="btn btn-primary">View</a>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="box-footer clearfix">
                        {{ $documents->links() }}
                    </div>
                </div>
            </div>
        </div>
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
