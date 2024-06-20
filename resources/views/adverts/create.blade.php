@extends('layouts.app')
@section('title', 'Create Advert')
@section("scripts")
<script src="https://cdnjs.cloudflare.com/ajax/libs/handlebars.js/4.7.8/handlebars.min.js" integrity="sha512-E1dSFxg+wsfJ4HKjutk/WaCzK7S2wv1POn1RRPGh8ZK+ag9l244Vqxji3r6wgz9YBf6+vhQEYJZpSjqWFPg9gg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script type="text/uppc-services" id="uppc-pricing">
    @php
        $pricing = array_map(fn($service):string=>$service['price'],config('constants.ADVERT_SERVICES'));
    @endphp
    @json($pricing)
</script>
<script type="text/uppc-services" id="uppc-meta">
    @php
        $service_map = array_reduce(config('constants.ADVERT_SERVICES'), function ($accumulator, $service) {
        $accumulator[$service['name']] = $service['meta'];
        return $accumulator;}, []);
    @endphp
    @json($service_map)
</script>
<script type="text/uppc-services" id="uppc-sources">
    @php
        $sources = array_map(fn($service):string=>$service['source'],config('constants.ADVERT_SERVICES'));
    @endphp
    @json($sources)
</script>
@verbatim
<script id="handlebars-template" type="text/x-handlebars-template">
    {{#each inputs}}
    <div class="form-group">
        <label>{{label}}</label>
        <input type="number" class="form-control" name="{{name}}" value="{{default}}" min="1" required>
    </div>
    {{/each}}
</script>
@endverbatim
<script>
    $(document).ready(()=>{
        const pricing = JSON.parse(document.getElementById("uppc-pricing").textContent);
        const service_map = JSON.parse(document.getElementById("uppc-meta").textContent);
        const sources = JSON.parse(document.getElementById("uppc-sources").textContent);
        $("#ad_category").on("change",function(){
            const service = parseInt($(this).val());
            $("#ad_amount").val(pricing[service]);
            $("#ad_source").text(sources[service]);
            // Compile Handlebars template
            const template = Handlebars.compile(document.getElementById("handlebars-template").textContent);
            const service_name = $(this).find("option:selected").text();
            const data = {'inputs':service_map[service_name]};
            const html = template(data);
            $("#meta-fields").html(html);
            if(data['inputs'].length){
                const inputName = data['inputs'][0]['name'];
                const inputElement = $("input[name='" + inputName + "']").first();
                inputElement.on("change",(e)=>{
                    $("#ad_amount").val(e.target.value * pricing[service]);
                });
            }

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

    });
</script>
@stop
@section('content')
<section class="content-header">
    <h1>
        Submit Advert
    </h1>
</section>
<div class="content">
    <div class="box box-primary">
        <div class="box-body">
            <div class="row">
                {!! Form::open(['route' => 'adverts.store','files'=>true]) !!}
                <div class="col-md-12">
                    <div class="form-group">
                        {!! Form::label('name', 'Applicant:') !!}
                        {!! Form::text('name', $user->name, ['class' => 'form-control', 'required' => 'required', 'readonly' => 'readonly']) !!}
                    </div>
                    <div class="form-group">
                        {!! Form::label('ad_category', 'Category') !!}
                        @php
                        $services = array_map(fn($service):string=>$service['name'],config('constants.ADVERT_SERVICES'));
                        @endphp
                        {!! Form::select('ad_category', $services, null, ['class' => 'form-control','id'=>'ad_category','required' => 'required']) !!}
                    </div>
                    <div id="meta-fields">
                    </div>
                    <div class="form-group">
                        {!! Form::label('ad_subtitle', 'Subtitle:') !!}
                        {!! Form::text('ad_subtitle','', ['class' => 'form-control', 'required' => 'required']) !!}
                    </div>
                    {!! Form::bsTextarea('description', null, ['class'=>'form-control b-wysihtml5-editor']) !!}
                    <div class="form-group">
                        <label for="file" class="control-label">
                            Scanned PDF
                            &lpar; <span id="ad_source">URSB</span> &rpar;
                        </label>
                        <div class="col-md-12">
                            {!! Form::file('file_scan', ['class'=>'form-control']) !!} <!-- File upload field -->
                        </div>
                    </div>
                    <h4 style="margin-top:10px;">Payment Details</h4>
                    <div class="form-group">
                        {!! Form::label('ad_amount', 'Amount:') !!}
                        {!! Form::number('ad_amount',config("constants.ADVERT_SERVICES")[0]['price'], ['class' => 'form-control', 'required' => 'required', 'readonly' => 'readonly',"id"=>'ad_amount']) !!}
                    </div>
                    <div class="form-group">
                        {!! Form::label('payment_type', 'Payment Method') !!}
                        {!! Form::select('payment_type', ["MOBILE"=>"MOBILE","CARD"=>"CARD"], null, ['class' => 'form-control', 'required' => 'required','id'=>'payment_type']) !!}
                    </div>
                    <div class="form-group" id="mobile_network_group" style="display:none;">
                        {!! Form::label('ad_payment_mobile_network', 'Mobile Network:') !!}
                        {!! Form::select('ad_payment_mobile_network', config('constants.MOBILE_NETWORKS'), null, ['class' => 'form-control', 'required' => 'required']) !!}
                    </div>
                    <div class="form-group" id="mobile_no_group" style="display:none;">
                        {!! Form::label('ad_payment_mobile_no', 'Mobile No:') !!}
                        {!! Form::tel('ad_payment_mobile_no','', ['class' => 'form-control', 'required' => 'required', 'pattern' => '0[0-9]{9}']) !!}
                    </div>
                    <div class="form-group">
                        <div class="col-md-offset-8 col-md-4">
                            {!! Form::submit('Submit Advert', ['class' => 'btn btn-primary pull-right']) !!}
                        </div>
                    </div>
                </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>
</div>
@endsection