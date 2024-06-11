@extends('layouts.app')
@section('title', 'Publications')
@section("css")
<style>
    :root{
        --columns: 4;
    }
    .grid-container{
  display: grid;
  grid-template-columns: repeat(var(--columns), 1fr);
  gap: 0;
  list-style-type: none;
  padding: 0;
}
    .thumbnail img {
    width: 50%;
    height: auto;
    border-radius: 8px;
}

</style>
@stop
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
                    <div class="grid-container">
                        @foreach($documents as $document)
                        <div>
                            <div class="thumbnail">
                                <img src="https://s3-us-west-2.amazonaws.com/s.cdpn.io/881020/book{{ str_pad(rand(1, 34), 2, '0', STR_PAD_LEFT) }}.jpg" alt="Book" style="margin-top:10px;">
                                <div class="caption text-center">
                                    <h5 class="title">{{ $document->pub_title }} by {{$document->pub_author}}</h6>
                                    <p class="text-danger"><b> UGX. {{ $document->pub_fees }}</b></p>
                                    <p>
                                        <a href="{{ route('publications.show', ['publication' => $document->id]) }}" class="btn btn-primary" role="button">
                                            <i class="fa fa-eye"></i> View More
                                        </a>
                                    </p>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                <div class="box-footer clearfix">
                    {{ $documents->links() }}
                </div>
            </div>
        </div>
    </div>
</section>
@endsection