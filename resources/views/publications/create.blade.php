@extends('layouts.app')
@section('title', 'Publications Upload')
@section('content')
<section class="content-header">
    <h1>
        Add a Publication
    </h1>
</section>
<div class="content">
    <div class="box box-primary" style="width: 440px; margin-left:auto; margin-right:auto;">
        <div class="box-body">
            <h3>Upload Publication</h3>
            <div class="row">
                {!! Form::open(['route' => 'publications.store','files'=>true]) !!}
                <div class="col-md-12">
                    <div class="form-group">
                        {!! Form::label('pub_title', 'Title:') !!}
                        {!! Form::text('pub_title','', ['class' => 'form-control', 'required' => 'required']) !!}
                    </div>
                    <div class="form-group">
                        {!! Form::label('pub_fees', 'Price:') !!}
                        {!! Form::number('pub_fees',0, ['class' => 'form-control', 'required' => 'required']) !!}
                    </div>
                    <div class="form-group">
                        {!! Form::label('pub_author', 'Author') !!}
                        {!! Form::text('pub_author','', ['class' => 'form-control', 'required' => 'required']) !!}
                    </div>
                    <div class="form-group">
                        <label for="file" class="control-label">Scanned PDF</label>
                        {!! Form::file('file_scan', ['class'=>'form-control']) !!} <!-- File upload field -->
                    </div>
                    <div class="form-group">
                        <div class="col-md-offset-8 col-md-4">
                            {!! Form::submit('Submit', ['class' => 'btn btn-primary pull-right']) !!}
                        </div>
                    </div>
                </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>
</div>
@endsection