@extends('adminlte::page')

@section('content_header')
    <h1 class="m-0 text-dark">Horario</h1>
@stop

@section('content')

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-2">
                    <div class="sticky-top mb-3">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title">Leyenda</h4>
                            </div>
                            <div class="card-body">
                                <!-- the events -->
                                <div id="external-events">
                                    <div class="external-event bg-success">Asistió</div>
                                    <div class="external-event bg-danger">Faltó</div>
                                </div>
                            </div>
                            <!-- /.card-body -->
                        </div>
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">Empleado</h3>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-12">
                                        <label for="name" class="font-weight-bold">Nombre:</label>
                                        <p>{!! $empleado['nombre'] !!} {!! $empleado['apellidos'] !!}</p>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <label for="name" class="font-weight-bold">Fecha de Vinculación:</label>
                                        <p class="text-red">{!! $contrato['fecha_vinculacion'] !!}</p>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <label for="name" class="font-weight-bold">Tipo de Contrato:</label>
                                        <p>{!! $contrato->tipoContrato->tipo !!}</p>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="name" class="font-weight-bold">Plazo:</label>
                                        <p>{!! $contrato->tipoContrato->plazo !!} meses.</p>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <label for="name" class="font-weight-bold">Salario base</label>
                                        <p>{!! $contrato->salario_base !!}</p>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <label for="name" class="font-weight-bold">Días Laborales:</label>
                                        <ul class="list-unstyled">
                                            @foreach (json_decode($contrato->dias_laborales) as $dia)
                                                <li>{!! $dia !!}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- /.col -->
                <div class="col-md-10">
                    <div class="card card-primary">
                        <div class="card-body p-0">
                            <!-- THE CALENDAR -->
                            <div id="calendar"></div>
                        </div>
                        <!-- /.card-body -->
                    </div>
                    <!-- /.card -->
                </div>
                <!-- /.col -->
            </div>
            <!-- /.row -->
        </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->

@endsection

@section('js')
    <script>
        $(function() {

            /* initialize the external events
             -----------------------------------------------------------------*/
            function ini_events(ele) {
                ele.each(function() {

                    // create an Event Object (https://fullcalendar.io/docs/event-object)
                    // it doesn't need to have a start or end
                    var eventObject = {
                        title: $.trim($(this).text()) // use the element's text as the event title
                    }

                    // store the Event Object in the DOM element so we can get to it later
                    $(this).data('eventObject', eventObject)

                })
            }

            ini_events($('#external-events div.external-event'))

            /* initialize the calendar
             -----------------------------------------------------------------*/
            var date = new Date()
            var d = date.getDate(),
                m = date.getMonth(),
                y = date.getFullYear()

            var Calendar = FullCalendar.Calendar;

            var calendarEl = document.getElementById('calendar');

            var calendar = new Calendar(calendarEl, {
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek,timeGridDay'
                },
                themeSystem: 'bootstrap',
                events: {!! json_encode($events) !!},
                editable: false,
                droppable: false
            });

            calendar.render();
        })
    </script>
@endsection
