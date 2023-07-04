@extends('adminlte::page')

{{-- @section('plugins.Datatables', true)
@section('plugins.jquery-validation', true)
@section('plugins.DatatablesPlugins', true)
@section('plugins.TempusDominusBs4', true)
@section('plugins.Select2', true) --}}

@section('content_header')
    <h1 class="m-0 text-dark">Perfil de empleados</h1>
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


    <x-adminlte-card title="Lista de Perfil de empleados" theme="pink" icon="fas fa-tags" class="elevation-3" maximizable>
        <x-datatable :columns=$columns :data=$data id="tablaPerfilEmpleado" />
    </x-adminlte-card>

    {{-- Modales --}}
    <x-registermodal :route="route('perfil-empleados.store')" :fields="[
        [
            'name' => 'i_selectEmpleado',
            'label' => 'Empleado',
            'placeholder' => 'Seleccionar el empleado',
            'type' => 'select2_with_search',
        ],
        ['name' => 'i_profesion', 'label' => 'Profesion', 'placeholder' => 'Ingrese su profesión', 'type' => 'input'],
        [
            'name' => 'i_cuenta_bancaria',
            'label' => 'Cuenta bancaria',
            'placeholder' => 'Ingrese la cuenta bancaria',
            'type' => 'input',
        ],
    ]" title="Registrar Perfil" size="md"
        modalId="registrarPerfilEmpleadoModal" />


    <x-editmodal :route="route('perfil-empleados.update', ['id' => $perfilEmpleadoId])" :fields="[
        [
            'name' => 'e_selectEmpleado',
            'label' => 'Empleado',
            'placeholder' => 'Seleccionar el empleado',
            'type' => 'select2_with_search',
        ],
        ['name' => 'e_profesion', 'label' => 'Profesion', 'placeholder' => 'Ingrese su profesión', 'type' => 'input'],
        [
            'name' => 'e_cuenta_bancaria',
            'label' => 'Cuenta bancaria',
            'placeholder' => 'Ingrese la cuenta bancaria',
            'type' => 'input',
        ],
    ]" title='Editar Perfil' size='md'
        modalId='editarPerfilEmpleadoModal' route_id="editarFormulario" />


    <x-deletemodal title='Editar Perfil' size='md' modalId='eliminarPerfilEmpleadoModal' route_id="eliminarFormulario"
        :route="route('perfil-empleados.update', ['id' => $perfilEmpleadoId])" quetion='¿Está seguro que desa eliminar el perfil?' />

@endsection

@section('js')
    <script>
        var perfilEmpleadoDataRoute = '{{ route('perfil-empleados.data') }}';
        var empleadosDataRoute = '{{ route('empleados.search') }}';
        var csrfToken = '{{ csrf_token() }}';
    </script>
    <script>
        var openModals = [{
            'name': 'registrarPerfil',
            'onClick': () => {
                $('#registrarPerfilEmpleadoModal').modal('show'); // Invocar al modal de registro
            },
        }, {
            'name': 'editarPerfil',
            'onClick': () => {
                $('#editarPerfilEmpleadoModal').modal('show'); // Invocar al modal de edición
            },
        }, {
            'name': 'eliminarPerfil',
            'onClick': () => {
                $('#eliminarPerfilEmpleadoModal').modal('show'); // Invocar al modal de eliminación

            },
        }, ];

        function buscarOpcion(modalName) {
            return openModals.find(function(modal) {
                return modal.name === modalName;
            });
        }

        function editarPerfilEmpleado(button) {
            var perfilempleado = JSON.parse(button.getAttribute(
                'data-perfil-empleado')); // Analizar la cadena JSON en un objeto

            var selectValue = perfilempleado['empleado_id'];
            var selectText = perfilempleado['empleado'];
            var optionEmpleado = new Option(selectText, selectValue, true, true); // Crear una opción

            // Asignar los valores a los campos del modal
            $('#editarPerfilEmpleadoModal select[name="e_selectEmpleado"]').empty().append(optionEmpleado);
            $('#editarPerfilEmpleadoModal input[name="e_profesion"]').val(perfilempleado['profesión']);
            $('#editarPerfilEmpleadoModal input[name="e_cuenta_bancaria"]').val(perfilempleado['cuenta bancaria']);

            // Actualizar el atributo 'route' del componente EditModal con la nueva ruta
            var route = '{{ route('perfil-empleados.update', ['id' => ':id']) }}'
                .replace(':id', perfilempleado.id);
            $('#editarFormulario').attr('action', route);

            $('#editarPerfilEmpleadoModal').modal('show'); // Invocar al modal de edición
        }

        function eliminarPerfilEmpleado(id) {
            // Lógica para mostrar el mensaje de confirmación de eliminación
            var formId = 'eliminarFormulario';
            var route = '{{ route('perfil-empleados.destroy', ['id' => ':id']) }}'
                .replace(':id', id);
            $('#' + formId).attr('action', route);
            // y abrir el modal de eliminacións
            $('#eliminarPerfilEmpleadoModal').modal('show');
        }

        $(function() {

            var table = $('#tablaPerfilEmpleado').DataTable({
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
                        name: 'empleado'
                    },
                    {
                        data: 'profesión',
                        name: 'profesión',
                    },
                    {
                        data: 'cuenta bancaria',
                        name: 'cuenta bancaria',
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
                        text: '<i class="fa fa-plus"></i> Registrar Perfil',
                        className: 'btn btn-sm btn-primary bg-danger mx-1',
                        action: () => buscarOpcion('registrarPerfil').onClick(),
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

            function refreshPerfilDataTable() {
                $.ajax({
                    url: perfilEmpleadoDataRoute,
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
                            console.log('No se encontraron datos de Perfil de empleados.');
                        }
                    },
                    error: function(xhr, textStatus, error) {
                        console.log('Error al obtener los datos de Perfil de empleados: ' + error);
                    }
                });
            }

            function generateButtons(row) {
                var btnEdit =
                    '<button class="btn btn-xs btn-default text-primary mx-1 shadow" title="Edit" onclick="editarPerfilEmpleado(this)" data-perfil-empleado=\'' +
                    JSON.stringify(row) +
                    '\'><i class="fa fa-lg fa-fw fa-pen"></i></button> ';
                var btnDelete =
                    '<button class="btn btn-xs btn-default text-danger mx-1 shadow" title="Delete" onclick="eliminarPerfilEmpleado(' +
                    row.id +
                    ')"><i class="fa fa-lg fa-fw fa-trash"></i></button> ';

                return '<nobr>' + btnEdit + btnDelete + '</nobr>';
            }

            refreshPerfilDataTable();

            setInterval(refreshPerfilDataTable, 10000);
        });
    </script>
    <script>
        function initializeSelect2(selector, dataRoute, paramName) {
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
            initializeSelect2('#i_selectEmpleado', empleadosDataRoute, 'emp');
            initializeSelect2('#e_selectEmpleado', empleadosDataRoute, 'emp');
        });
    </script>
@endsection
