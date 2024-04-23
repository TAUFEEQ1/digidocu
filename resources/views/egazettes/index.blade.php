@extends('layouts.app')
@section('title', 'E-Gazettes')
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
                                <td>{{ $document->gaz_published_on }}</td>
                                <td>
                                    <a href="{{ route('subscriptions.show', $document->id) }}" class="btn btn-primary">
                                        <i class="fa fa-download"></i> Download
                                    </a>
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