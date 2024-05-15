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
        $("#ad_category").on("change",function(){
            const service = parseInt($(this).val());
            $("#ad_amount").val(pricing[service]);
            // Compile Handlebars template
            const template = Handlebars.compile(document.getElementById("handlebars-template").textContent);
            const service_name = $(this).find("option:selected").text();
            const html = template({'inputs':service_map[service_name]});
            $("#meta-fields").html(html);
        });

    });
</script>
@stop
@section('content')
<section class="content-header">
    <h1>
        Create Advert
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
                        <label for="file" class="control-label">Scanned PDF</label>
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
                        {!! Form::label('ad_payment_mobile_network', 'Mobile Network:') !!}
                        {!! Form::select('ad_payment_mobile_network', config('constants.MOBILE_NETWORKS'), null, ['class' => 'form-control', 'required' => 'required']) !!}
                    </div>
                    <div class="form-group">
                        {!! Form::label('ad_payment_mobile_no', 'Mobile No:') !!}
                        {!! Form::tel('ad_payment_mobile_no','', ['class' => 'form-control', 'required' => 'required', 'pattern' => '0[0-9]{9}']) !!}
                    </div>
                    <div class="form-group">
                        <div class="col-md-offset-8 col-md-4">
                            {!! Form::submit('Create Advert', ['class' => 'btn btn-primary pull-right']) !!}
                        </div>
                    </div>
                </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>
</div>
@endsection