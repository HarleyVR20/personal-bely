@extends('adminlte::page')

{{-- @section('plugins.Datatables', true)
@section('plugins.jquery-validation', true)
@section('plugins.DatatablesPlugins', true) --}}
{{-- @section('plugins.TempusDominusBs4', true) --}}
{{-- @section('plugins.Select2', true) --}}

@section('content_header')
    <h1 class="m-0 text-dark">Asistencias</h1>
@stop

@section('content')
    @if (count($errors) > 0)
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <div class="d-flex align-items-center">
                <i class="fas fa-exclamation-circle mr-2"></i>
                <div>
                    <strong>¡Ups!</strong> Hubo algunos problemas con tu entrada.
                </div>
            </div>
            <ul class="mt-2 mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    @if ($message = Session::get('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <div class="d-flex align-items-center">
                <i class="fas fa-check-circle mr-2"></i>
                <div>
                    {{ $message }}
                </div>
            </div>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    <x-adminlte-card title="Lista de Asistencias" theme="pink" icon="fas fa-tags" class="elevation-3" maximizable>
        <x-datatable :columns=$columns :data=$data id="tablaAsistencia" />
    </x-adminlte-card>

    {{-- Modales --}}
    <x-registermodal :route="route('asistencias.store')" :fields="[
        [
            'name' => 'i_selectEmpleado',
            'label' => 'Empleado',
            'placeholder' => 'Seleccionar el empleado',
            'type' => 'select2_with_search',
        ],
        [
            'name' => 'i_area',
            'label' => 'Área',
            'placeholder' => 'Seleccionar el área',
            'type' => 'select2_with_search',
        ],
        [
            'name' => 'i_asistencia',
            'label' => 'Asistencia',
            'options' => [
                ['value' => '1', 'label' => 'Asistió', 'selected' => 'true'],
                ['value' => '0', 'label' => 'No asistió'],
            ],
            'type' => 'radio',
        ],
        [
            'name' => 'i_fecha',
            'label' => 'Día',
            'placeholder' => 'Ingrese el día',
            'title' => 'Día',
            'config' => 'only_date',
            'type' => 'datetime',
        ],
        [
            'name' => 'i_hora_entrada',
            'label' => 'Hora de entrada',
            'placeholder' => 'Ingrese la hora de entrada',
            'title' => 'Hora de entrada',
            'config' => 'only_hour',
            'type' => 'datetime',
        ],
        [
            'name' => 'i_hora_salida',
            'label' => 'Hora de salida',
            'placeholder' => 'Ingrese la hora de salida',
            'title' => 'Hora de salida',
            'config' => 'only_hour',
            'type' => 'datetime',
        ],
    ]" title="Registrar Asistencia" size="md"
        modalId="registrarAsistenciaModal" />


    <x-editmodal :route="route('asistencias.update', ['id' => $asistenciaId])" :fields="[
        [
            'name' => 'e_selectEmpleado',
            'label' => 'Empleado',
            'placeholder' => 'Seleccionar el empleado',
            'type' => 'select2_with_search',
        ],
        [
            'name' => 'e_area',
            'label' => 'Área',
            'placeholder' => 'Seleccionar el área',
            'type' => 'select2_with_search',
        ],
        [
            'name' => 'e_asistencia',
            'label' => 'Asistencia',
            'options' => [
                ['value' => '1', 'label' => 'Asistió', 'selected' => 'true'],
                ['value' => '0', 'label' => 'No asistió'],
            ],
            'type' => 'radio',
        ],
        [
            'name' => 'e_fecha',
            'label' => 'Día',
            'placeholder' => 'Ingrese el día',
            'title' => 'Día',
            'config' => 'only_date',
            'type' => 'datetime',
        ],
        [
            'name' => 'e_hora_entrada',
            'label' => 'Hora de entrada',
            'placeholder' => 'Ingrese la hora de entrada',
            'title' => 'Hora de entrada',
            'config' => 'only_hour',
            'type' => 'datetime',
        ],
        [
            'name' => 'e_hora_salida',
            'label' => 'Hora de salida',
            'placeholder' => 'Ingrese la hora de salida',
            'title' => 'Hora de salida',
            'config' => 'only_hour',
            'type' => 'datetime',
        ],
        ['name' => 'e_id', 'type' => 'hidden'],
    ]" title='Editar Asistencia' size='md'
        modalId='editarAsistenciaModal' route_id="editarFormulario" />


    <x-deletemodal title='Editar Asistencia' size='md' modalId='eliminarAsistenciaModal' :route="route('asistencias.destroy', ['id' => $asistenciaId])"
        quetion='¿Está seguro que desa eliminar la asistencia?' :field="['name' => 'd_id']" route_id="eliminarFormulario" />

    <x-register-excel-modal title='Importar asistencias' modalId='registrarAsistenciaExcelModal' :field="[
        'name' => 'excel_file',
        'label' => 'Seleccionar archivo Excel',
        'placeholder' => 'Seleccione un archivo',
    ]" :route="route('asistencias.import')" />
@endsection

@section('js')
    <!-- Definición de variables de ruta y token CSRF -->
    <script>
        var asistenciaDataRoute = '{{ route('asistencias.data') }}';
        var csrfToken = '{{ csrf_token() }}';
    </script>
    <!-- Configuración de modales -->
    <script>
        var openModals = [{
            'name': 'registrarAsistencia',
            'onClick': () => {
                // Lógica para abrir el modal de registro de asistencia
                $('#registrarAsistenciaModal').modal('show');
            },
        }, {
            'name': 'registrarAsistenciaExcel',
            'onClick': () => {
                // Lógica para abrir el modal de registro de asistencia por Excel
                $('#registrarAsistenciaExcelModal').modal('show');
            },
        }, {
            'name': 'editarAsistencia',
            'onClick': (button) => {
                // Lógica para editar una asistencia
                var asistencia = JSON.parse(button.getAttribute(
                    'data-asistencia')); // Analizar la cadena JSON en un objeto

                // Asignar los valores a los campos del modal
                // ...
                var selectValue_1 = asistencia['empleado_id'];
                var selectText_1 = asistencia['empleado'];
                var option_emp = new Option(selectText_1, selectValue_1, true, true); // Crear una opción

                var selectValue_2 = asistencia['area_id'];
                var selectText_2 = asistencia['área'];
                var option_area = new Option(selectText_2, selectValue_2, true, true); // Crear una opción
                // Vaciar el select y agregar la opción creada

                $('#editarAsistenciaModal select[name="e_selectEmpleado"]').empty().append(option_emp);
                $('#editarAsistenciaModal select[name="e_area"]').empty().append(option_area);
                $('#editarAsistenciaModal input[name="e_id"]').val(asistencia.id);
                $('#editarAsistenciaModal input[name="e_fecha"]').val(asistencia['día']);

                $('#editarAsistenciaModal input[name="e_asistencia"][value="1"]').prop('checked',
                    asistencia['estado'] === 1);
                $('#editarAsistenciaModal input[name="e_asistencia"][value="0"]').prop('checked',
                    asistencia['estado'] === 0);

                $('#editarAsistenciaModal input[name="e_hora_entrada"]').prop('disabled', false);
                $('#editarAsistenciaModal input[name="e_hora_salida"]').prop('disabled', false);
                if (asistencia['estado'] === 0) {
                    $('#editarAsistenciaModal input[name="e_hora_entrada"]').prop('disabled', true);
                    $('#editarAsistenciaModal input[name="e_hora_salida"]').prop('disabled', true);
                }

                $('#editarAsistenciaModal input[name="e_hora_entrada"]').val(asistencia['hora entrada']);
                $('#editarAsistenciaModal input[name="e_hora_salida"]').val(asistencia['hora salida']);

                // Actualizar el atributo 'route' del componente EditModal con la nueva ruta
                var route = '{{ route('asistencias.update', ['id' => ':id']) }}'
                    .replace(':id', asistencia.id);
                $('#editarFormulario').attr('action', route);

                $('#editarAsistenciaModal').modal('show'); // Invocar al modal de edición
            },
        }, {
            'name': 'eliminarAsistencia',
            'onClick': (id) => {

                // Lógica para mostrar el mensaje de confirmación de eliminación
                var formId = 'eliminarFormulario';
                var route = '{{ route('asistencias.destroy', ['id' => ':id']) }}'
                    .replace(':id', id);
                $('#' + formId).attr('action', route);
                // y abrir el modal de eliminacións
                $('#eliminarAsistenciaModal').modal('show');
            },
        }, ];

        function buscarOpcion(modalName) {
            return openModals.find(function(modal) {
                return modal.name === modalName;
            });
        }

        // <!-- Inicialización del DataTable -->
        $(function() {
            var table = $('#tablaAsistencia').DataTable({
                columns: [
                    // Definición de las columnas del DataTable
                    {
                        data: 'id',
                        name: 'id',
                        visible: false,
                        orderable: false,
                        searchable: false,
                    },
                    {
                        data: 'empleado_id',
                        name: 'empleado_id',
                        visible: false,
                        orderable: false,
                        searchable: false,
                    },
                    {
                        data: 'empleado',
                        name: 'empleado',
                    },
                    {
                        data: 'area_id',
                        name: 'area_id',
                        visible: false,
                        orderable: false,
                        searchable: false,
                    },
                    {
                        data: 'área',
                        name: 'área',
                    },
                    {
                        data: 'día',
                        name: 'día',
                    },
                    {
                        data: 'hora entrada',
                        name: 'hora entrada',
                    },
                    {
                        data: 'hora salida',
                        name: 'hora salida',
                    },
                    {
                        data: 'estado',
                        name: 'estado',
                        visible: false,
                        orderable: false,
                        searchable: false,
                    },
                    {
                        data: 'creado en',
                        name: 'creado en',
                        visible: false,
                        orderable: false,
                        searchable: false,
                    },
                    {
                        data: 'actualizado en',
                        name: 'actualizado en',
                        visible: false,
                        orderable: false,
                        searchable: false,
                    },
                    {
                        data: 'id',
                        name: 'opciones',
                        orderable: false,
                        searchable: false,
                        render: function(data, type, row) {
                            return generateButtons(row);
                        },
                    }
                ],
                // Configuración adicional del DataTable
                timeout: 5000, // Tiempo de espera en milisegundos (5 segundos)
                lengthMenu: [
                    [10, 25, 50, -1],
                    [10, 25, 50, 'Todo']
                ],
                pageLength: 10,
                searching: true,
                ordering: true,
                order: [
                    [0, 'asc']
                ],
                language: {
                    url: 'https://cdn.datatables.net/plug-ins/1.11.2/i18n/es_es.json'
                },
                dom: "<'row'<'col-auto'l><'col'B><'col-auto'f>>" +
                    "<'row'<'col-sm-12't>>" +
                    "<'row'<'col-sm-12 col-md-5'><'col-sm-12 col-md-7'p>>",

                buttons: [{
                        extend: 'copy',
                        text: '<i class="fas fa-sticky-note text-yellow"></i>', //Copiar
                        className: 'btn btn-sm btn-default',
                    },
                    {
                        extend: 'csv',
                        text: '<i class="fas fa-file-csv text-blue"></i>', //CSV
                        className: 'btn btn-sm btn-default',
                    },
                    {
                        extend: 'excel',
                        text: '<i class="fas fa-file-excel text-green"></i>', //Excel
                        className: 'btn btn-sm btn-default',
                    },
                    {
                        extend: 'pdf',
                        text: '<i class="fas fa-file-pdf text-red"></i>', //PDF
                        className: 'btn btn-sm btn-default',
                    },
                    {
                        extend: 'print',
                        text: '<i class="fa fa-print"></i>',
                        className: 'btn btn-sm btn-default',
                    },
                    {
                        text: '<i class="fa fa-plus"></i> Registrar Asistencia',
                        className: 'btn btn-sm btn-primary bg-danger mx-1',
                        action: () => buscarOpcion('registrarAsistencia').onClick(),
                    },
                    {
                        text: '<i class="fa fa-list"></i> Cargar asistencias',
                        className: 'btn btn-sm btn-primary bg-danger mx-1',
                        action: () => buscarOpcion('registrarAsistenciaExcel').onClick(),
                    },
                    {
                        extend: 'colvis',
                    },
                ],
                responsive: true,
                paging: true,
                stateDuration: -1,
                info: true,
                select: {
                    style: 'multi',
                    selector: 'td:first-child'
                },
            });

            function initializeDataTable(data) {
                table.clear().rows.add(data).draw();
            }
            // Actualizar los datos del DataTable al cargar la página
            function refreshAsistenciaDataTable() {
                // Lógica para actualizar los datos del DataTable a través de una petición AJAX
                $.ajax({
                    url: asistenciaDataRoute,
                    type: 'POST',
                    dataType: 'json',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken
                    },
                    success: function(response) {
                        if (response.data) {
                            // console.log('Datos encontrados: \n ' + response.data);
                            initializeDataTable(response.data);
                        } else {
                            console.log('No se encontraron datos de Asistencias.');
                        }
                    },
                    error: function(xhr, textStatus, error) {
                        console.log('Error al obtener los datos de Asistencias: ' + error);
                    }
                });
            }

            function generateButtons(row) {
                // Generar los botones de edición y eliminación para cada fila del DataTable
                var btnEdit =
                    '<button class="btn btn-xs btn-default text-primary mx-1 shadow" title="Edit" onclick="buscarOpcion(\'' +
                    'editarAsistencia' + '\').onClick(this)" data-asistencia = \'' + JSON.stringify(row) +
                    '\'><i class="fa fa-lg fa-fw fa-pen"></i></button> ';
                var btnDelete =
                    '<button class="btn btn-xs btn-default text-danger mx-1 shadow" title="Delete" onclick="buscarOpcion(\'' +
                    'eliminarAsistencia' + '\').onClick(' +
                    row.id + ')"><i class="fa fa-lg fa-fw fa-trash"></i></button> ';

                return '<nobr>' + btnEdit + btnDelete + '</nobr>';
            }

            refreshAsistenciaDataTable();
            // Actualizar los datos del DataTable cada 10 segundos
            setInterval(refreshAsistenciaDataTable, 10000);
        });
    </script>
    <!-- Inicialización de select2 -->
    <script>
        var empleadoDataRoute = "{{ route('empleados.search') }}";
        var areaDataRoute = '{{ route('areas.search') }}';

        function initializeSelect2(selector, dataRoute, paramName) {
            // Lógica para inicializar el plugin select2 en un elemento HTML específico
            $(selector).select2({
                placeholder: 'Buscar opción',
                minimumInputLength: 2,
                ajax: {
                    url: dataRoute,
                    type: 'POST',
                    dataType: 'json',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken
                    },
                    delay: 250,
                    data: function(params) {
                        var requestData = {};
                        requestData[paramName] = params.term;
                        return requestData;
                    },
                    processResults: function(data) {
                        return {
                            results: data,
                        };
                    },
                    cache: true
                },
                templateResult: function(option) {
                    if (option.loading) {
                        return $('<div class="loading-results">Buscando...</div>');
                    }
                    return $('<div>' + option.text + '</div>');
                },
                templateSelection: function(option) {
                    return option.text;
                }
            });
        }

        $(function() {
            // Inicializar select2 en los elementos correspondientes
            initializeSelect2('#i_selectEmpleado', empleadoDataRoute, 'emp');
            initializeSelect2('#e_selectEmpleado', empleadoDataRoute, 'emp');
            initializeSelect2('#i_area', areaDataRoute, 'q');
            initializeSelect2('#e_area', areaDataRoute, 'q');

        });
    </script>
    <!-- Lógica para habilitar/deshabilitar campos de hora -->
    <script>
        $(document).ready(function() {
            // Obtener referencias a los elementos relevantes
            const asistenciaRadio = $('#registrarAsistenciaModal input[name="i_asistencia"]');
            const horaEntradaInput = $('#registrarAsistenciaModal input[name="i_hora_entrada"]');
            const horaSalidaInput = $('#registrarAsistenciaModal input[name="i_hora_salida"]');

            const eAsistenciaRadio = $('#editarAsistenciaModal input[name="e_asistencia"]');
            const ehoraEntradaInput = $('#editarAsistenciaModal input[name="e_hora_entrada"]');
            const ehoraSalidaInput = $('#editarAsistenciaModal input[name="e_hora_salida"]');

            asistenciaRadio.on('change', function() {
                const isChecked = $(this).val() === '1';
                horaEntradaInput.prop('disabled', !isChecked);
                horaSalidaInput.prop('disabled', !isChecked);
            });
            eAsistenciaRadio.on('change', function() {
                const isCheckedE = $(this).val() === '1';
                ehoraEntradaInput.prop('disabled', !isCheckedE);
                ehoraSalidaInput.prop('disabled', !isCheckedE);
            });
            // Establecer el estado inicial de los campos de hora
            const isChecked = asistenciaRadio.val() === '1';
            const isCheckedE = eAsistenciaRadio.val() === '1';
            horaEntradaInput.prop('disabled', !isChecked);
            horaSalidaInput.prop('disabled', !isChecked);
            ehoraEntradaInput.prop('disabled', !isCheckedE);
            ehoraSalidaInput.prop('disabled', !isCheckedE);
        });
    </script>
@endsection
