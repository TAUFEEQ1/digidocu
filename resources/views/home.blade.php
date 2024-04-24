@extends('layouts.app')
@section('title', 'Portal Home')
@section("css")
<style>
    .card-text{
        font-size: 15px;
    }
</style>
@stop
@section("content")

<div class="container">
    <div class="row">
        <div class="col-md-12 text-center">
            <h1>Welcome to Our Portal</h1>
            <p>Access all the resources you need with ease.</p>
        </div>
    </div>

    <div class="row mt-5">
        <div class="col-md-4">
            <div class="box text-center">
                <div class="box-body">
                    <h4>Subscribe</h4>
                    <p class="card-text">Stay updated with the latest news and updates.</p>
                    <a href="{{ route('subscriptions.create') }}" class="btn btn-primary">Subscribe Now</a>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="box text-center">
                <div class="box-body">
                    <h4>Download Latest Gazette</h4>
                    <p class="card-text">Access the most recent official documents.</p>
                    <a href="{{ route('egazettes.index') }}" class="btn btn-primary">Download Now</a>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="box text-center">
                <div class="box-body">
                    <h4>Apply For Ads </h4>
                    <p class="card-text">Apply for an advert in the Gazette.</p>
                    <a href="{{ route('adverts.create') }}" class="btn btn-primary">Apply Now</a>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="box text-center">
                <div class="box-body">
                    <h4>More Resources</h4>
                    <p class="card-text">Additional resources available on the portal.</p>
                    <a href="#" class="btn btn-primary">Explore</a>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
