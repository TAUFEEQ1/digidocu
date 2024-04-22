@extends('layouts.app')
@section('title', 'Subscriptions')
@section("scripts")
<script>
    $(document).ready(()=>{
        const today = new Date();
        console.log(today.toISOString().split('T')[0]);
        $("#sub_end_date").val(today.toISOString().split('T')[0])
    })
</script>
@stop
@section('content')
<section class="content-header">
    <h1>
        Subscribe
    </h1>
</section>
<div class="content">
    <div class="box box-primary">
        <div class="box-body">
            <div class="row">
                {!! Form::open(['route' => 'subscriptions.store']) !!}
                <div class="col-md-12">
                    <div class="form-group">
                        {!! Form::label('name', 'Name:') !!}
                        {!! Form::text('name', $user->name, ['class' => 'form-control', 'required' => 'required', 'readonly' => 'readonly']) !!}
                    </div>
                    <div class="form-group">
                        {!! Form::label('sub_type', 'Subscription Type:') !!}
                        {!! Form::select('sub_type', config('constants.SUB_TYPES'), null, ['class' => 'form-control', 'required' => 'required']) !!}
                    </div>
                    <div class="form-group">
                        {!! Form::label('sub_end_date', 'End Date:') !!}
                        {!! Form::date('sub_end_date', null, ['id' => 'sub_end_date','class' => 'form-control', 'readonly' => 'readonly']) !!}
                    </div>
                    <h3>Payment Details</h3>
                    <div class="form-group">
                        {!! Form::label('sub_payment_mobile_network', 'Mobile Network:') !!}
                        {!! Form::select('sub_payment_mobile_network', config('constants.MOBILE_NETWORKS'), null, ['class' => 'form-control', 'required' => 'required']) !!}
                    </div>
                    <div class="form-group">
                        {!! Form::label('sub_payment_mobile_no', 'Mobile No:') !!}
                        {!! Form::text('sub_payment_mobile_no','', ['class' => 'form-control', 'required' => 'required']) !!}
                    </div>
                    <div class="form-group">
                        <div class="col-md-offset-8 col-md-4">
                            {!! Form::submit('Subcribe', ['class' => 'btn btn-primary pull-right']) !!}
                        </div>
                    </div>
                </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>
</div>
@endsection