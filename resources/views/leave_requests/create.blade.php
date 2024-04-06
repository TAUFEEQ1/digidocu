@extends('layouts.app')
@section('title', "Add Letter")
@section("scripts")
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        // Function to calculate business days between two dates
        function getBusinessDaysCount(startDate, endDate) {
            var businessDays = 0;
            var currentDate = moment(startDate);
            var endDate = moment(endDate);

            // Loop through each day and count business days
            while (currentDate <= endDate) {
                if (currentDate.day() !== 0 && currentDate.day() !== 6) {
                    businessDays++;
                }
                currentDate.add(1, 'days');
            }
            return businessDays;
        }

        // Function to update the "Number of days taken" field
        function updateNumberOfDaysTaken() {
            var startDate = document.getElementById('start_date').value;
            var endDate = document.getElementById('end_date').value;

            // Calculate business days
            var businessDays = getBusinessDaysCount(startDate, endDate);

            // Update the "Number of days taken" field
            document.getElementById('number_of_days_taken').value = businessDays;
        }

        // Call the function when start date or end date changes
        document.getElementById('start_date').addEventListener('change', updateNumberOfDaysTaken);
        document.getElementById('end_date').addEventListener('change', updateNumberOfDaysTaken);
    });
</script>
@stop
@section('content')
<section class="content-header">
    <h1>
        Submit Leave Request
    </h1>
</section>
<div class="content">
    <div class="box box-primary">
        <div class="box-body">
            <div class="row">
                {!! Form::open(['route' => 'leave_requests.store']) !!}

                <div class="col-md-6">
                    <div class="form-group">
                        {!! Form::label('name', 'Name:') !!}
                        {!! Form::text('name', $user->name, ['class' => 'form-control', 'required' => 'required', 'readonly' => 'readonly']) !!}
                    </div>


                    <div class="form-group">
                        {!! Form::label('designation', 'Designation:') !!}
                        {!! Form::text('lv_designation', null, ['class' => 'form-control', 'required' => 'required']) !!}
                    </div>

                    <div class="form-group">
                        {!! Form::label('department', 'Department:') !!}
                        {!! Form::select('lv_department', config('constants.DEPARTMENTS'), null, ['class' => 'form-control', 'required' => 'required']) !!}
                    </div>
                    <div class="form-group">
                        {!! Form::label('leave_type', 'Leave Type:') !!}
                        {!! Form::select('lv_type', ['Annual' => 'Annual', 'Sick' => 'Sick', 'Maternity' => 'Maternity', 'Paternity' => 'Paternity', 'Compassionate' => 'Compassionate', 'Terminal' => 'Terminal / Long term illness', 'Study' => 'Study leave with pay', 'Without_pay' => 'Leave without pay'], null, ['class' => 'form-control', 'required' => 'required']) !!}
                    </div>
                </div>



                <div class="col-md-6">


                    <div class="form-group">
                        {!! Form::label('line_manager', 'Line Manager:') !!}
                        {!! Form::select('lv_line_manager_id', $senior_managers, null, ['class' => 'form-control', 'required' => 'required']) !!}
                    </div>

                    <div class="form-group">
                        {!! Form::label('outstanding_leave_days', 'Outstanding Leave days:') !!}
                        {!! Form::number('outstanding_leave_days', $user->outstanding_leave_days, ['class' => 'form-control', 'readonly' => 'readonly']) !!}
                    </div>

                    <div class="form-group">
                        {!! Form::label('start_date', 'Start Date:') !!}
                        {!! Form::date('lv_start_date', null, ['id' => 'start_date', 'class' => 'form-control', 'required' => 'required']) !!}
                    </div>

                    <div class="form-group">
                        {!! Form::label('end_date', 'End Date:') !!}
                        {!! Form::date('lv_end_date', null, ['id' => 'end_date', 'class' => 'form-control', 'required' => 'required']) !!}
                    </div>


                    <div class="form-group">
                        {!! Form::label('number_of_days_taken', 'Number of days taken:') !!}
                        {!! Form::number('number_of_days_taken', null, ['id' => 'number_of_days_taken','class' => 'form-control', 'readonly' => 'readonly']) !!}
                    </div>

                    <div class="form-group">
                        <div class="col-md-offset-8 col-md-4">
                            {!! Form::submit('Submit', ['class' => 'btn btn-primary pull-right']) !!}
                        </div>
                    </div>
                </div>
                {!! Form::close() !!}

            </div>
        </div>
    </div>
</div>
@endsection