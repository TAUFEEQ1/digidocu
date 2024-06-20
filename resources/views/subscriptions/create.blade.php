@extends('layouts.app')
@section('title', 'Subscriptions')
@section("scripts")
<script type="text/uppc-fees" id="uppc-fees">
    @json(config("constants.SUB_FEES"))
</script>
<script type="text/uppc-sub-types" id="uppc-sub-types">
    @json(config("constants.SUB_TYPES"))
</script>
<script src="https://cdn.jsdelivr.net/npm/moment@2.30.1/moment.min.js"></script>
<script>
    function getEndDate(sub_type, today) {
        switch (sub_type) {
            case 'One-Off':
                today.add(1, 'days');
                break;
            case '3-Months':
                today.add(3, 'months');
                break;
            case '6-Months':
                today.add(6, 'months');
                break;
            default:
                today.add(1, 'years');
        }
        return today.format('YYYY-MM-DD');
    }
    $(document).ready(() => {
        const sub_fees = JSON.parse($("#uppc-fees").text());
        const sub_types = JSON.parse($("#uppc-sub-types").text());
        $("#sub_end_date").val(getEndDate('ANNUAL', moment()));
        $("#sub_type").on("change", (e) => {
            const selected = parseInt(e.target.value);
            const sub_type = sub_types[selected];
            const today = moment();
            $("#sub_amount").val(sub_fees[selected]);
            $("#sub_end_date").val(getEndDate(sub_type, today));
        });

        function toggleMobileFields() {
            if ($('#payment_type').val() === 'MOBILE') {
                $('#mobile_network_group').show();
                $('#mobile_no_group').show();
            } else {
                $('#mobile_network_group').hide();
                $('#mobile_no_group').hide();
            }
        }
        // Initial check when the page loads
        toggleMobileFields();

        // Event listener for the payment type dropdown change
        $('#payment_type').change(function() {
            toggleMobileFields();
        });
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
                        {!! Form::select('sub_type', config('constants.SUB_TYPES'), null, ['class' => 'form-control','id'=>'sub_type','required' => 'required']) !!}
                    </div>
                    <div class="form-group">
                        {!! Form::label('sub_category', 'Subscription Category:') !!}
                        {!! Form::select('sub_category', config('constants.SUB_CATEGORY'), null, ['class' => 'form-control','id'=>'sub_category','required' => 'required']) !!}
                    </div>
                    <div class="form-group">
                        {!! Form::label('sub_end_date', 'End Date:') !!}
                        {!! Form::date('sub_end_date', null, ['id' => 'sub_end_date','class' => 'form-control', 'readonly' => 'readonly']) !!}
                    </div>
                    <h3>Payment Details</h3>
                    <div class="form-group">
                        {!! Form::label('sub_amount', 'Amount:') !!}
                        {!! Form::text('sub_amount',config("constants.SUB_FEES")[0], ['class' => 'form-control', 'required' => 'required', 'readonly' => 'readonly']) !!}
                    </div>
                    <div class="form-group">
                        {!! Form::label('payment_type', 'Payment Method:') !!}
                        {!! Form::select('payment_type', ["MOBILE"=>"MOBILE","CARD"=>"CARD"], null, ['class' => 'form-control', 'required' => 'required','id' => 'payment_type']) !!}
                    </div>
                    <div class="form-group" id="mobile_network_group" style="display:none;">
                        {!! Form::label('sub_payment_mobile_network', 'Mobile Network:') !!}
                        {!! Form::select('sub_payment_mobile_network', config('constants.MOBILE_NETWORKS'), null, ['class' => 'form-control', 'required' => 'required']) !!}
                    </div>
                    <div class="form-group" id="mobile_no_group" style="display:none;">
                        {!! Form::label('sub_payment_mobile_no', 'Mobile No:') !!}
                        {!! Form::tel('sub_payment_mobile_no','', ['class' => 'form-control', 'required' => 'required', 'pattern' => '0[0-9]{9}']) !!}
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