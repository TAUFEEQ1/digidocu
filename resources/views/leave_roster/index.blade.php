@extends('layouts.app')
@section('title', "Leave Roster")
@section("css")
<link href="https://cdn.jsdelivr.net/npm/@fullcalendar/core/main.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/@fullcalendar/daygrid/main.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/@fullcalendar/timegrid/main.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/@fullcalendar/list/main.min.css" rel="stylesheet">
<style>
  #calendar {
    height: 600px;
    margin-top: 10px;

  }
  .bg-danger{
    background-color: red;
  }
</style>
@stop
@section("scripts")
<script src="
https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.js
"></script>
<script type="text/diggidoc" id="documents">
  @json($documents)
</script>
<script>

  document.addEventListener('DOMContentLoaded', function() {
    const documents = JSON.parse(document.getElementById("documents").textContent);
    const calendarEl = document.getElementById('calendar')
    const calendar = new FullCalendar.Calendar(calendarEl, {
      initialView: 'dayGridMonth',
      events: documents,
      eventClick:function(info){
            $('#modalTitle').html(info.event.title);
            const status = info.event.extendedProps['status'];
            const variant = status.includes("APPROVED")?"label-success":"label-danger";
            $('#status').html(status).removeClass("label-success","label-danger").addClass(variant);
            $("#created_by").html(info.event.extendedProps["created_by"]);
            $("#leave_type").html(info.event.extendedProps["type"]);
            $("#starts_on").html(info.event.start);
            $("#ends_on").html(info.event.end);
            $('#calendarModal').modal();
      }
    })
    calendar.render()
  })
</script>
@stop
@section("content")
<div id="calendarModal" class="modal fade">
<div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span> <span class="sr-only">close</span></button>
            <h4 id="modalTitle" class="modal-title"></h4>
        </div>
        <div class="modal-body">
          <ul class="list-unstyled">
            <li>
              <b>Name:</b> <span id="created_by"></span>
            </li>
            <li>
              <b>Status:</b> <span id="status" class="label"></span>
            </li>
            <li>
                <b>Type:</b> <span id="leave_type"></span>
            </li>
            <li>
              <b>Started On:</b> <span id="starts_on"></span>
            </li>
            <li>
              <b>Ends On:</b><span id="ends_on"></span>
            </li>
          </ul>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
    </div>
</div>
</div>
<div class="box">
  <div class="box-body">
    <div id="calendar"></div>
  </div>
</div>
@endsection