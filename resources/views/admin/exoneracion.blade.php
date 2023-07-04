@extends('adminlte::page')

{{-- @section('plugins.Datatables', true)
@section('plugins.jquery-validation', true)
@section('plugins.DatatablesPlugins', true)
@section('plugins.TempusDominusBs4', true)
@section('plugins.Select2', true) --}}

@section('content_header')
    <h1 class="m-0 text-dark">Exoneraciones</h1>
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

    <x-adminlte-card title="Lista de Exoneraciones" theme="pink" icon="fas fa-tags" class="elevation-3" maximizable>
        <x-datatable :columns=$columns :data=$data id="tablaExoneracion" />
    </x-adminlte-card>


    {{-- Modales --}}
    <x-registermodal :route="route('exoneraciones.store')" :fields="[
        [
            'name' => 'i_selectEmpleado',
            'label' => 'Empleado',
            'placeholder' => 'Seleccionar el empleado',
            'type' => 'select2_with_search',
        ],
        [
            'name' => 'i_selectMotivoExioneracion',
            'label' => 'Motivo de exoneración',
            'placeholder' => 'Seleccionar el motivo de exoneración',
            'type' => 'select2_with_search',
        ],
        [
            'name' => 'i_finicio',
            'label' => 'fecha de inicio',
            'placeholder' => '',
            'title' => 'fecha de inicio',
            'config' => 'only_date',
            'type' => 'datetime',
        ],
        [
            'name' => 'i_ffinal',
            'label' => 'fecha final',
            'placeholder' => '',
            'title' => 'fecha final',
            'config' => 'only_date',
            'type' => 'datetime',
        ],
        [
            'name' => 'i_observacion',
            'label' => 'Observación',
            'placeholder' => 'Ingrese las observaciones',
            'type' => 'long_text',
        ],
    ]" title="Registrar Exoneracion" size="md"
        modalId="registrarExoneracionModal" />


    <x-editmodal :route="route('exoneraciones.update', ['id' => $exoneracionId])" :fields="[
        [
            'name' => 'e_selectEmpleado',
            'label' => 'Empleado',
            'placeholder' => 'Seleccionar el empleado',
            'type' => 'select2_with_search',
        ],
        [
            'name' => 'e_selectMotivoExioneracion',
            'label' => 'Motivo de exoneración',
            'placeholder' => 'Seleccionar el motivo de exoneración',
            'type' => 'select2_with_search',
        ],
        [
            'name' => 'e_finicio',
            'label' => 'fecha de inicio',
            'placeholder' => '',
            'title' => 'fecha de inicio',
            'config' => 'only_date',
            'type' => 'datetime',
        ],
        [
            'name' => 'e_ffinal',
            'label' => 'fecha final',
            'placeholder' => '',
            'title' => 'fecha final',
            'config' => 'only_date',
            'type' => 'datetime',
        ],
        [
            'name' => 'e_observacion',
            'label' => 'Observación',
            'placeholder' => 'Ingrese las observaciones',
            'type' => 'long_text',
        ],
        ['name' => 'e_id', 'type' => 'hidden'],
    ]" title='Editar Exoneracion' size='md'
        modalId='editarExoneracionModal' route_id="editarFormulario" />


    <x-deletemodal title='Eliminar Exoneracion' size='md' modalId='eliminarExoneracionModal'
        route_id="eliminarFormulario" :route="route('exoneraciones.update', ['id' => $exoneracionId])" quetion='¿Está seguro que desa eliminar la exoneracion?' />

@endsection

@section('js')
    <script>
        var exoneracionesDataRoute = '{{ route('exoneraciones.data') }}';
        var csrfToken = '{{ csrf_token() }}';
    </script>
    <script>
        var openModals = [{
            'name': 'registrarExoneracion',
            'onClick': () => {
                $('#registrarExoneracionModal').modal('show'); // Invocar al modal de registro
            },
        }, {
            'name': 'editarExoneracion',
            'onClick': () => {
                $('#editarExoneracionModal').modal('show'); // Invocar al modal de edición
            },
        }, {
            'name': 'eliminarExoneracion',
            'onClick': () => {
                $('#eliminarExoneracionModal').modal('show'); // Invocar al modal de eliminación

            },
        }, ];

        function buscarOpcion(modalName) {
            return openModals.find(function(modal) {
                return modal.name === modalName;
            });
        }

        function editarExoneracion(button) {
            var exoneracion = JSON.parse(button.getAttribute('data-exoneracion')); // Analizar la cadena JSON en un objeto

            var selectValue = exoneracion['empleado_id'];
            var selectText = exoneracion['empleado'];
            var optionEmp = new Option(selectText, selectValue, true, true); // Crear una opción

            var selectValue = exoneracion['motivo_de_exoneración_id'];
            var selectText = exoneracion['motivo de exoneración'];
            var optionMotivoEx = new Option(selectText, selectValue, true, true); // Crear una opción

            // Asignar los valores a los campos del modal
            $('#editarExoneracionModal input[name="e_id"]').val(exoneracion.id);
            $('#editarExoneracionModal select[name="e_selectEmpleado"]').empty().append(optionEmp);
            $('#editarExoneracionModal select[name="e_selectMotivoExioneracion"]').empty().append(optionMotivoEx);

            $('#editarExoneracionModal input[name="e_finicio"]').datetimepicker('date', exoneracion['fecha inicio']);
            $('#editarExoneracionModal input[name="e_ffinal"]').datetimepicker('date', exoneracion['fecha final']);
            $('#editarExoneracionModal textarea[name="e_observacion"]').text(exoneracion['observación']);

            // Actualizar el atributo 'route' del componente EditModal con la nueva ruta
            var route = '{{ route('exoneraciones.update', ['id' => ':id']) }}'
                .replace(':id', exoneracion.id);
            $('#editarFormulario').attr('action', route);

            buscarOpcion('editarExoneracion').onClick();

        }

        function eliminarExoneracion(id) {

            var formId = 'eliminarFormulario';
            var route = '{{ route('exoneraciones.destroy', ['id' => ':id']) }}'
                .replace(':id', id);
            $('#' + formId).attr('action', route);

            // Lógica para mostrar el mensaje de confirmación de eliminación
            // y abrir el modal de eliminacións
            buscarOpcion('eliminarExoneracion').onClick();
        }

        $(function() {

            var table = $('#tablaExoneracion').DataTable({
                columns: [{
                        data: 'id',
                        name: 'id',
                        visible: false,
                    },
                    {
                        data: 'empleado_id',
                        name: 'empleado_id',
                        visible: false,
                    },
                    {
                        data: 'empleado',
                        name: 'empleado',
                    },
                    {
                        data: 'motivo_de_exoneración_id',
                        name: 'motivo_de_exoneración_id',
                        visible: false,
                    },
                    {
                        data: 'motivo de exoneración',
                        name: 'motivo de exoneración',
                    },
                    {
                        data: 'fecha inicio',
                        name: 'fecha inicio',
                    },
                    {
                        data: 'fecha final',
                        name: 'fecha final'
                    },
                    {
                        data: 'observación',
                        name: 'observación'
                    },
                    {
                        data: 'creado en',
                        name: 'creado en',
                        visible: false,
                    },
                    {
                        data: 'actualizado en',
                        name: 'actualizado en',
                        visible: false,
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
                        text: '<i class="fa fa-plus"></i> Registrar Exoneracion',
                        className: 'btn btn-sm btn-primary bg-danger mx-1',
                        action: () => buscarOpcion('registrarExoneracion').onClick(),
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

            function refreshExoneracionDataTable() {
                $.ajax({
                    url: exoneracionesDataRoute,
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
                            console.log('No se encontraron datos de Exoneraciones.');
                        }
                    },
                    error: function(xhr, textStatus, error) {
                        console.log('Error al obtener los datos de Exoneraciones: ' + error);
                    }
                });
            }

            function generateButtons(row) {
                var btnEdit =
                    '<button class="btn btn-xs btn-default text-primary mx-1 shadow" title="Edit" onclick="editarExoneracion(this)" data-exoneracion=\'' +
                    JSON.stringify(row) +
                    '\'><i class="fa fa-lg fa-fw fa-pen"></i></button> ';
                var btnDelete =
                    '<button class="btn btn-xs btn-default text-danger mx-1 shadow" title="Delete" onclick="eliminarExoneracion(' +
                    row.id +
                    ')"><i class="fa fa-lg fa-fw fa-trash"></i></button> ';

                return '<nobr>' + btnEdit + btnDelete + '</nobr>';
            }

            refreshExoneracionDataTable();

            setInterval(refreshExoneracionDataTable, 10000);
        });
    </script>
    <script>
        var empleadoDataRoute = "{{ route('empleados.search') }}";
        var motivoExoDataRoute = "{{ route('motivo-exoneraciones.search') }}";

        $(function() {
            $('#i_selectEmpleado').select2({
                placeholder: 'Buscar opción',
                minimumInputLength: 2,
                ajax: {
                    url: empleadoDataRoute, // Reemplaza 'ruta_post.php' con tu propia ruta POST
                    type: 'POST',
                    dataType: 'json',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken
                    },
                    delay: 250,
                    data: function(params) {
                        return {
                            emp: params.term
                        };
                    },
                    processResults: function(results) {
                        return {
                            results: results,
                        };
                    },
                    cache: true
                },
                templateResult: function(option) {
                    // Personaliza la apariencia de cada opción en el dropdown
                    if (option.loading) {
                        return $('<div class="loading-results">Buscando...</div>');
                    }

                    return $('<div>' + option.text + '</div>');
                },
                templateSelection: function(option) {
                    // Personaliza la apariencia de la selección actual
                    return option.text;
                }
            });
            $('#i_selectMotivoExioneracion').select2({
                placeholder: 'Buscar opción',
                minimumInputLength: 2,
                ajax: {
                    url: motivoExoDataRoute, // Reemplaza 'ruta_post.php' con tu propia ruta POST
                    type: 'POST',
                    dataType: 'json',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken
                    },
                    delay: 250,
                    data: function(params) {
                        return {
                            q: params.term
                        };
                    },
                    processResults: function(data) {
                        return {
                            results: data
                        };
                    },
                    cache: true
                },
                templateResult: function(option) {
                    // Personaliza la apariencia de cada opción en el dropdown
                    if (option.loading) {
                        return $('<div class="loading-results">Buscando...</div>');
                    }

                    return $('<div>' + option.text + '</div>');
                },
                templateSelection: function(option) {
                    // Personaliza la apariencia de la selección actual
                    return option.text;
                }
            });
            $('#e_selectEmpleado').select2({
                placeholder: 'Buscar opción',
                minimumInputLength: 2,
                ajax: {
                    url: empleadoDataRoute, // Reemplaza 'ruta_post.php' con tu propia ruta POST
                    type: 'POST',
                    dataType: 'json',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken
                    },
                    delay: 250,
                    data: function(params) {
                        return {
                            emp: params.term
                        };
                    },
                    processResults: function(results) {
                        return {
                            results: results,
                        };
                    },
                    cache: true
                },
                templateResult: function(option) {
                    // Personaliza la apariencia de cada opción en el dropdown
                    if (option.loading) {
                        return $('<div class="loading-results">Buscando...</div>');
                    }

                    return $('<div>' + option.text + '</div>');
                },
                templateSelection: function(option) {
                    // Personaliza la apariencia de la selección actual
                    return option.text;
                }
            });
            $('#e_selectMotivoExioneracion').select2({
                placeholder: 'Buscar opción',
                minimumInputLength: 2,
                ajax: {
                    url: motivoExoDataRoute, // Reemplaza 'ruta_post.php' con tu propia ruta POST
                    type: 'POST',
                    dataType: 'json',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken
                    },
                    delay: 250,
                    data: function(params) {
                        return {
                            q: params.term
                        };
                    },
                    processResults: function(data) {
                        return {
                            results: data
                        };
                    },
                    cache: true
                },
                templateResult: function(option) {
                    // Personaliza la apariencia de cada opción en el dropdown
                    if (option.loading) {
                        return $('<div class="loading-results">Buscando...</div>');
                    }

                    return $('<div>' + option.text + '</div>');
                },
                templateSelection: function(option) {
                    // Personaliza la apariencia de la selección actual
                    return option.text;
                }
            });

        });
    </script>
@endsection
