@extends('layouts.app')

@section('title', 'View E-Gazette')
@section("css")
<link rel="stylesheet" href="{{asset('css/pdfviewer.jquery.css')}}">
<style>
    #btn-download{
        display: none;
    }
    #btn-print{
        display: none;
    }
</style>
@stop
@section("scripts")
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.6.347/pdf.min.js" integrity="sha512-Z8CqofpIcnJN80feS2uccz+pXWgZzeKxDsDNMD/dJ6997/LSRY+W4NmEt9acwR+Gt9OHN0kkI1CTianCwoqcjQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="{{asset('js/pdfviewer.jquery.js')}}"></script>
<script>
$('#pdfviewer').pdfViewer("{{route('egazettes.view',['id'=>$document->id])}}",{ 
  width: 1280,
  height: 800,
});
</script>
@stop
@section('content')
<div id="pdfviewer" style="width: 100%; height: 600px;"></div>
@endsection