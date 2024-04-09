@extends('layouts.app')
@section('title', "Create Cash Request")
@section("scripts")
<script src="https://cdn.jsdelivr.net/npm/number-to-words@1.2.4/numberToWords.min.js"></script>
<script>
    // Get references to the amount input field and the disabled input field for words
    var amountInput = document.getElementById('amount');
    var amountInWordsInput = document.getElementById('amount_in_words');

    // Add an event listener to the amount input field
    amountInput.addEventListener('input', function() {
        // Get the value of the amount input field
        var amountValue = this.value;

        // Call the numbertowords function to get words representation
        var words = numberToWords.toWords(amountValue).charAt(0).toUpperCase() + numberToWords.toWords(amountValue).slice(1) + ' shillings';

        // Update the value of the disabled input field with the words
        amountInWordsInput.value = words;
    });
</script>
@stop
@section('content')
<section class="content-header">
    <h1>
        Submit Cash Request
    </h1>
</section>
<div class="content">
    <div class="box box-primary">
        <div class="box-body">
            {!! Form::open(['route' => 'cash_requests.store']) !!}
            <div class="form-group">
                {!! Form::label('name', 'Name:') !!}
                {!! Form::text('name', $user->name, ['class' => 'form-control', 'required' => 'required', 'readonly' => 'readonly']) !!}
            </div>
            <div class="form-group">
                {!! Form::label('department', 'Department:') !!}
                {!! Form::select('cr_department', config('constants.DEPARTMENTS'), null, ['class' => 'form-control', 'required' => 'required']) !!}
            </div>
            <div class="form-group">
                {!! Form::label('title', 'Title:') !!}
                {!! Form::text('cr_title', null, ['class' => 'form-control', 'required' => 'required']) !!}
            </div>

            <div class="form-group">
                {!! Form::label('Purpose', 'Purpose:') !!}
                {!! Form::textarea('cr_purpose', null, ['class'=>'form-control b-wysihtml5-editor']) !!}
            </div>

            <div class="form-group">
                <label for="file" class="control-label">Scanned PDF</label>
                {!! Form::file('file_scan', ['class'=>'form-control']) !!} <!-- File upload field -->
            </div>

            <div class="form-group">
                {!! Form::label('amount', 'Amount:') !!}
                {!! Form::number('cr_amount', null, ['class' => 'form-control','required' => 'required', 'id' => 'amount']) !!}
            </div>

            <div class="form-group">
                {!! Form::label('amount_in_words', 'Amount in Words:') !!}
                {!! Form::text('amount_in_words', null, ['class' => 'form-control', 'id' => 'amount_in_words', 'disabled' => 'disabled']) !!}
            </div>

            <div class="form-group">
                {!! Form::submit('Submit', ['class' => 'btn btn-primary pull-right']) !!}
            </div>

            {!! Form::close() !!}

        </div>
    </div>
</div>
@endsection