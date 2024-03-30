@extends('layouts.app')
@section('title', "Add Letter")
@section('content')
    <section class="content-header">
        <h1>
            Letter
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
                        {!! Form::file('file') !!} <!-- File upload field -->
                        {!! Form::submit('Submit', ['class' => 'btn btn-primary']) !!}
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
@endsection
