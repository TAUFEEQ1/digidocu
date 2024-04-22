@extends('layouts.app')
@section('title', 'Subscriptions')
@section('content')
    <section class="content-header">
        <h1 class="pull-left">Subscriptions</h1>
        <div class="pull-right">
            <a href="{{ route('subscriptions.create') }}" class="btn btn-primary">
                <i class="fa fa-plus"></i>
                Add New
            </a>
        </div>
    </section>
    <section class="content" style="margin-top: 20px;">
        <div class="clearfix"></div>
        <div class="clearfix"></div>
        <div class="row"> 
            <div class="col-md-12">
                <div class="box">
                    <div class="box-header with-border">
                        <h3 class="box-title">Subscriptions List</h3>
                        <div class="box-tools">
                            <form action="{{ route('subscriptions.index') }}" method="GET" class="form-inline">
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
                                    <th>Type</th>
                                    <th>Status</th>
                                    <th>Payment Status</th>
                                    <th>Created At</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($documents as $document)
                                <tr>
                                    <td>{{ $document->createdBy->name }}</td>
                                    <td>{{ $document->sub_type }}</td>
                                    <td>{{ $document->status }}</td>
                                    <td>{{ $document->sub_payment_status }} </td>
                                    <td>{{ $document->created_at }}</td>
                                    <td>
                                        <a href="{{ route('subscriptions.show', $document->id) }}" class="btn btn-primary">View</a>
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
    </section>
@endsection