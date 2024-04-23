@extends('layouts.app')
@section('title', 'E-Gazette Upload')
@section('content')
<section class="content-header">
    <h1>
        Publish Gazette
    </h1>
</section>
<div class="content">
    <div class="box box-primary" style="width: 440px; margin-left:auto; margin-right:auto;">
        <div class="box-body">
            <h3>Publish Gazette</h3>
            <div class="row">
                {!! Form::open(['route' => 'egazettes.store','files'=>true]) !!}
                <div class="col-md-12">
                    <div class="form-group">
                        {!! Form::label('gaz_issue_no', 'Issue No:') !!}
                        {!! Form::text('gaz_issue_no','', ['class' => 'form-control', 'required' => 'required']) !!}
                    </div>
                    <div class="form-group">
                        {!! Form::label('gaz_sub_category', 'Sub Category:') !!}
                        {!! Form::text('gaz_sub_category','', ['class' => 'form-control', 'required' => 'required']) !!}
                    </div>
                    <div class="form-group">
                        {!! Form::label('gaz_published_on', 'Published On') !!}
                        {!! Form::date('gaz_published_on','', ['class' => 'form-control', 'required' => 'required']) !!}
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