@extends('layouts.app')
@section('title', 'Subscriptions')
@section("css")
<style>
    .badge-success {
        background-color: #5cc45e;
    }

    .badge-success:hover {
        background-color: #356635;
    }

    .badge-warning {
        background-color: #f89406;
    }

    .badge-warning:hover {
        background-color: #c67605;
    }

    .badge-error {
        background-color: #b94a48;
    }

    .badge-error:hover {
        background-color: #953b39;
    }
</style>
@stop
@section('content')
<section class="content-header">
    <h1 class="pull-left">Subscriptions</h1>
    @if($user->is_client && !$user->is_subscribed)
    <div class="pull-right">
        <a href="{{ route('subscriptions.create') }}" class="btn btn-primary">
            <i class="fa fa-plus"></i>
            Add New
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
                                <td>
                                    @if($document->status == config('constants.SUB_STATUSES.ACTIVE'))
                                    <span class="text-success">
                                        <b>{{ $document->status }}</b>
                                    </span>
                                    @else
                                    {{ $document->status }}
                                    @endif
                                </td>
                                <td>
                                    @php
                                    $status = $document->sub_payment_status;
                                    $badgeClass = '';

                                    switch ($status) {
                                    case 'COMPLETED':
                                    $badgeClass = 'badge-success';
                                    break;
                                    case 'PENDING':
                                    $badgeClass = 'badge-warning';
                                    break;
                                    case 'FAILED':
                                    $badgeClass = 'badge-error';
                                    break;
                                    default:
                                    $badgeClass = 'badge-secondary'; // Default to secondary badge if status is unknown
                                    break;
                                    }
                                    @endphp

                                    <span class="badge {{ $badgeClass }}">
                                        {{ $status }}
                                    </span>
                                </td>

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