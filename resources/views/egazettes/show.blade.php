@extends('layouts.app')

@section('title', 'View E-Gazette')
@section("scripts")
<script src="{{asset('/lib/webviewer.min.js')}}"></script>
<script>
  WebViewer({
    path: '{{ asset("lib")}}', // path to the PDF.js Express'lib' folder on your server
    licenseKey: '{{env("PDFJS_EXPRESS_KEY")}}',
    initialDoc: '{{route("egazettes.view",["id"=>$document->id])}}',
    // initialDoc: '/path/to/my/file.pdf',  // You can also use documents on your server
  }, document.getElementById('viewer'))
  .then(instance => {
    // now you can access APIs through the WebViewer instance
    const { Core, UI } = instance;

    // adding an event listener for when a document is loaded
    Core.documentViewer.addEventListener('documentLoaded', () => {
      console.log('document loaded');
    });

    // adding an event listener for when the page number has changed
    Core.documentViewer.addEventListener('pageNumberUpdated', (pageNumber) => {
      console.log(`Page number is: ${pageNumber}`);
    });
    instance.UI.disableElements(['downloadButton','printButton']);
  });
</script>
@stop
@section('content')
<div id="viewer" style="width:100%;height:90vh;"></div>
@endsection