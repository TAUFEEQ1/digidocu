@extends('layouts.app')
@section('title', "Add Letter")
@section('content')
<section class="content-header">
    <h1>
        Submit Letter
    </h1>
</section>
<div class="content">
    <div class="box box-primary">
        <div class="box-body">
            <div class="row">
                {!! Form::open(['route' => 'letters.store', 'files' => true]) !!} <!-- Add 'files' => true for file upload -->
                {!! Form::bsText('subject') !!}
                {!! Form::bsText('sender') !!}
                {!! Form::bsTextarea('description', null, ['class'=>'form-control b-wysihtml5-editor']) !!}
                {!! Form::bsText('sending_entity', null, ['placeholder' => 'Sending Entity']) !!} <!-- Add input field for sending_entity -->
                <div class="form-group">
                    <label for="file" class="col-md-3 control-label">Scanned PDF</label>
                    <div class="col-md-6">
                        {!! Form::file('file_scan', ['class'=>'form-control']) !!} <!-- File upload field -->
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-md-offset-8 col-md-4">
                        {!! Form::submit('Submit', ['class' => 'btn btn-primary pull-right']) !!}
                    </div>
                </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>
</div>
@endsection