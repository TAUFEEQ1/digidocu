@extends('layouts.app')
@section('title', 'Publications')
@section("content")
<section class="content-header">
    <h1 class="pull-left"></h1>
    @can("create egazette")
    <div class="pull-right">
        <a href="{{ route('publications.create') }}" class="btn btn-primary">
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
                    <h3 class="box-title">Publications List</h3>
                    <div class="box-tools">
                        <form action="{{ route('publications.index') }}" method="GET" class="form-inline">
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
                                <th>Title</th>
                                <th>Price</th>
                                <th>Author</th>
                                <th>Created At</th>
                                @if ($user->is_client)
                                <th>Action</th> 
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($documents as $document)
                            <tr>
                                <td>{{ $document->pub_title }}</td>
                                <td>{{ $document->pub_fees }}</td>
                                <td>{{ $document->pub_author }}</td>
                                <td>{{ $document->created_at }}</td>
                                @if ($user->is_client)
                                <td>
                                    @if($document->bought)
                                    <button class="btn btn-primary">Download</button>
                                    @else
                                    <button class="btn btn-primary">Buy</button>
                                    @endif
                                </td>
                                @endif
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