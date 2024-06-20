@extends('layouts.app')
@section('title', 'Adverts')
@section("content")
<section class="content-header">
    <h1 class="pull-left">Adverts</h1>
    @if($user->is_client)
    <div class="pull-right">
        <a href="{{ route('adverts.create') }}" class="btn btn-primary">
            <i class="fa fa-plus"></i>
            Submit Advert
        </a>
    </div>
    @endif
</section>
<section class="content" style="margin-top: 20px;">
    <div class="clearfix"></div>
    <div class="clearfix"></div>
    <div class="row">
        <div class="col-md-12">
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title">Advert List</h3>
                    <div class="box-tools">
                        <form action="{{ route('adverts.index') }}" method="GET" class="form-inline">
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
                                <th>Applicant</th>
                                <th>Sub Title</th>
                                <th>Category</th>
                                <th>Status</th>
                                <th>Created At</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($documents as $document)
                            <tr>
                                <td>{{ $document->createdBy->name }}</td>
                                <td>{{ $document->ad_subtitle }}</td>
                                <td>{{ $document->ad_category}}</td>
                                <td>{{ $document->status }}</td>
                                <td>{{ $document->created_at }}</td>
                                <td>
                                    <a href="{{ route('adverts.show', $document->id) }}" class="btn btn-primary">View</a> 
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