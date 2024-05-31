@extends('layouts.app')
@section('title',"Show Publication")
@section('css')
<style>
    .box.custom-box {
        border: 1px solid black;
        box-shadow: 0 1px 2px 1px rgba(0, 0, 0, 0.08)
    }

    .box.custom-box .box-header {
        background-color: black;
        color: #fff;
        padding: 3px 5px;
    }

    .custom-box .user-block>.username,
    .custom-box .user-block>.description {
        margin-left: 0;
    }

    .custom-box .box-body img {
        height: 145px;
        object-fit: contain;
        width: 100%;
        border-radius: 3px;
    }

    object.obj-file-box {
        height: 80vh;
        object-fit: contain;
        width: 100%;
        border: 1px solid rgba(0, 40, 100, 0.2);
        box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
    }

    .img-d-select .icheckbox_square-blue {
        position: absolute;
        right: 0;
        top: 0;
    }

    #sticky_footer {
        position: fixed;
        bottom: -4px;
        right: 10px;
    }
</style>
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
@stop
@section('scripts')
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
@section('content')
<section class="content-header" style="margin-bottom: 27px;">
    <h1 class="pull-left">
        Publication
        <small>{{$document->pub_title}}</small>
    </h1>
    <div class="content">
        <div class="clearfix"></div>
        <div class="clearfix"></div>
        <div class="row">
            <div class="col-sm-3">
                <div class="box box-primary">
                    <div class="box-body">
                        <div class="form-group">
                            <label>Title:</label>
                            <p>{{ $document->pub_title }}</p>
                        </div>
                        <div class="form-group">
                            <label>Fees (UGX):</label>
                            <p>{!! number_format($document->pub_fees, 0, '.', ',') !!}</p>
                        </div>
                        <div class="form-group">
                            <label>Status:</label>
                            @if ($document->status==config('constants.ADVERT_STATES.PAID'))
                            <span class="label label-success">{{$document->status}}</span>
                            @elseif($document->status==config('constants.ADVERT_STATES.PENDING PAYMENT'))
                            <span class="label label-info">{{$document->status}}</span>
                            @else
                            <span class="label label-warning">{{$document->status}}</span>
                            @endif
                        </div>
                        <div class="form-group">
                            <label>Author:</label> {{$document->pub_author}}
                        </div>
                        <div class="form-group">
                            <label>Created At:</label>
                            <p>{!! formatDateTime($document->created_at) !!} <br>
                                ({{\Carbon\Carbon::parse($document->created_at)->diffForHumans()}})
                            </p>
                        </div>
                        <div class="form-group">
                            <label>Last Updated:</label>
                            <p>{!! formatDateTime($document->updated_at) !!} <br>
                                ({{\Carbon\Carbon::parse($document->updated_at)->diffForHumans()}})
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-9">
                <div class="nav-tabs-custom">
                    <ul class="nav nav-tabs">
                        @if ($user->is_client)
                        <li class="active">
                            <a href="#tab_buy" data-toggle="tab">
                                Buy
                            </a>
                        </li>
                        @endif
                    </ul>
                    <div class="tab-content">
                        @if ($user->is_client)
                        <div class="tab-pane active" id="tab_buy">
                            @if(!$document->is_bought)
                            {!! Form::open(['route' => ['publications.buy', $document->id], 'method' => 'post']) !!}
                                <div class="form-group">
                                    {!! Form::label('name', 'Name:') !!}
                                    {!! Form::text('name', $user->name, ['class' => 'form-control', 'required' => 'required', 'readonly' => 'readonly']) !!}
                                </div>
                                <h3>Payment Details</h3>
                                <div class="form-group">
                                    {!! Form::label('mobile_network', 'Mobile Network:') !!}
                                    {!! Form::select('mobile_network', config('constants.MOBILE_NETWORKS'), null, ['class' => 'form-control', 'required' => 'required']) !!}
                                </div>
                                <div class="form-group">
                                    {!! Form::label('mobile_no', 'Mobile No:') !!}
                                    {!! Form::tel('mobile_no','', ['class' => 'form-control', 'required' => 'required', 'pattern' => '0[0-9]{9}']) !!}
                                </div>
                                <div class="form-group">
                                    {!! Form::label('pub_fees', 'Fees:') !!}
                                    {!! Form::text('pub_fees',$document->pub_fees, ['class' => 'form-control', 'required' => 'required', 'readonly' => 'readonly']) !!}
                                </div>
                                <div class="form-group">
                                    {!! Form::submit('Buy', ['class' => 'btn btn-primary']) !!}
                                </div>
                            {!! Form::close() !!}
                            @elseif($document->being_bought)
                            <span class="badge badge-primary">PAYMENT PENDING</span>
                            @else
                            <input type="hidden" name="passkey" value="{{ $document->pub_key }}">
                            <button class="btn btn-primary key-copy" value="{{ $document->pub_key }}">
                                <i class="fa fa-copy"></i>
                            </button>
                            <button class="btn btn-primary" value="{{route('publications.download',['id'=>$document->id])}}">
                                <i class="fa fa-download"></i> Download
                            </button>
                            @endif
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection