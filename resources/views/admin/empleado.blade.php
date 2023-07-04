@extends('adminlte::page')

{{-- @section('plugins.Datatables', true)
@section('plugins.jquery-validation', true)
@section('plugins.DatatablesPlugins', true)
@section('plugins.TempusDominusBs4', true)
@section('plugins.Select2', true) --}}


@section('content_header')
    <h1 class="m-0 text-dark">Empleados</h1>
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

    <x-adminlte-card title="Lista de Empleados" theme="pink" icon="fas fa-tags" class="elevation-3" maximizable>
        <x-datatable :columns=$columns :data=$data id="empleadosTabla" />
    </x-adminlte-card>


    {{-- Modales --}}
    <x-registermodal :route="route('empleados.store')" :fields="[
        ['name' => 'i_nombre', 'label' => 'Nombre', 'placeholder' => 'Ingrese el nombre', 'type' => 'input'],
        ['name' => 'i_apellidos', 'label' => 'Apellidos', 'placeholder' => 'Ingrese los apellidos', 'type' => 'input'],
        ['name' => 'i_dni', 'label' => 'dni', 'placeholder' => 'Ingrese el DNI', 'type' => 'input'],
        [
            'name' => 'i_fnacimiento',
            'label' => 'fecha de nacimiento',
            'placeholder' => 'Ingrese la fecha de nacimiento',
            'title' => 'fecha de nacimiento',
            'config' => 'only_date',
            'type' => 'datetime',
        ],
        [
            'name' => 'i_domicilio',
            'label' => 'Domicilio Fiscal',
            'placeholder' => 'Ingrese el domicilio',
            'type' => 'input',
        ],
        [
            'name' => 'i_celular',
            'label' => 'Número de celular',
            'placeholder' => 'Ingrese el número de celular',
            'type' => 'input',
        ],
        ['name' => 'i_correo', 'label' => 'Correo', 'placeholder' => 'Ingrese el correo', 'type' => 'input'],
    ]" title="Registrar Empleado" size="md"
        modalId="registrarEmpleadoModal" />


    <x-editmodal :route="route('empleados.update', ['id' => $empleadoId])" :fields="[
        ['name' => 'e_nombre', 'label' => 'Nombre', 'placeholder' => 'Ingrese el nombre', 'type' => 'input'],
        ['name' => 'e_apellidos', 'label' => 'Apellidos', 'placeholder' => 'Ingrese los apellidos', 'type' => 'input'],
        ['name' => 'e_dni', 'label' => 'dni', 'placeholder' => 'Ingrese el DNI', 'type' => 'input'],
        [
            'name' => 'e_fnacimiento',
            'label' => 'fecha de nacimiento',
            'placeholder' => 'Ingrese la fecha de nacimiento',
            'title' => 'fecha de nacimiento',
            'config' => 'only_date',
            'type' => 'datetime',
        ],
        [
            'name' => 'e_domicilio',
            'label' => 'Domicilio Fiscal',
            'placeholder' => 'Ingrese el domicilio',
            'type' => 'input',
        ],
        [
            'name' => 'e_celular',
            'label' => 'Número de celular',
            'placeholder' => 'Ingrese el número de celular',
            'type' => 'input',
        ],
        ['name' => 'e_correo', 'label' => 'Correo', 'placeholder' => 'Ingrese el correo', 'type' => 'input'],
        ['name' => 'e_id', 'type' => 'hidden'],
    ]" title='Editar Empleado' size='md' modalId='editarEmpleadoModal'
        route_id="editarFormulario" />


    <x-deletemodal title='Eliminar Empleado' size='md' modalId='eliminarEmpleadoModal' route_id="eliminarFormulario"
        :route="route('empleados.destroy', ['id' => $empleadoId])" quetion='¿Está seguro que desa eliminar el empleado?' :field="['name' => 'd_id']" />

@endsection

@section('js')
    <script>
        var empleadosDataRoute = '{{ route('empleados.data') }}';
        var CsrfToken = '{{ csrf_token() }}';
    </script>
    <script>
        // function registrarEmpleado() {
        //     // Lógica para registrar una Producto
        //     $('#registrarEmpleadoModal').modal('show');
        // }
        var openModals = [{
            'name': 'registrarEmpleado',
            'onClick': () => {
                $('#registrarEmpleadoModal').modal('show'); // Invocar al modal de registro
            },
        }, {
            'name': 'editarEmpleado',
            'onClick': () => {
                $('#editarEmpleadoModal').modal('show'); // Invocar al modal de edición
            },
        }, {
            'name': 'eliminarEmpleado',
            'onClick': () => {
                $('#eliminarEmpleadoModal').modal('show'); // Invocar al modal de eliminación

            },
        }, ];

        function buscarOpcion(modalName) {
            return openModals.find(function(modal) {
                return modal.name === modalName;
            });
        }

        function editarEmpleado(button) {
            var empleado = JSON.parse(button.getAttribute('data-empleado')); // Analizar la cadena JSON en un objeto

            // Asignar los valores a los campos del modal
            $('#editarEmpleadoModal input[name="e_id"]').val(empleado.id);
            $('#editarEmpleadoModal input[name="e_nombre"]').val(empleado.nombre);
            $('#editarEmpleadoModal input[name="e_apellidos"]').val(empleado.apellidos);
            $('#editarEmpleadoModal input[name="e_dni"]').val(empleado.dni);
            $('#editarEmpleadoModal input[name="e_fnacimiento"]').val(empleado['fecha nacimiento']);
            $('#editarEmpleadoModal input[name="e_domicilio"]').val(empleado['domicilio fiscal']);
            $('#editarEmpleadoModal input[name="e_celular"]').val(empleado['número de celular']);
            $('#editarEmpleadoModal input[name="e_correo"]').val(empleado.correo);

            // Actualizar el atributo 'route' del componente EditModal con la nueva ruta
            var route = '{{ route('empleados.update', ['id' => ':id']) }}'
                .replace(':id', empleado.id);
            $('#editarFormulario').attr('action', route);

            buscarOpcion('editarEmpleado').onClick();
        }

        function eliminarEmpleado(id) {

            var formId = 'eliminarFormulario';
            var route = '{{ route('empleados.destroy', ['id' => ':id']) }}'
                .replace(':id', id);
            $('#' + formId).attr('action', route);

            // Lógica para mostrar el mensaje de confirmación de eliminación
            // y abrir el modal de eliminacións
            buscarOpcion('eliminarEmpleado').onClick();
        }

        $(function() {
            var table = $('#empleadosTabla').DataTable({
                columns: [{
                        data: 'id',
                        name: 'id',
                        visible: false,
                    },
                    {
                        data: 'nombre',
                        name: 'nombre',
                    },
                    {
                        data: 'apellidos',
                        name: 'apellidos',
                    },
                    {
                        data: 'dni',
                        name: 'dni',
                    },
                    {
                        data: 'fecha nacimiento',
                        name: 'fecha nacimiento',
                    },
                    {
                        data: 'domicilio fiscal',
                        name: 'domicilio fiscal',
                    },
                    {
                        data: 'número de celular',
                        name: 'número de celular',
                    },
                    {
                        data: 'correo',
                        name: 'correo',
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
                        text: '<i class="fa fa-plus"></i> Registrar Empleado',
                        className: 'btn btn-sm btn-primary bg-danger mx-1',
                        action: () => buscarOpcion('registrarEmpleado').onClick(),
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

            function refreshEmpleadoDataTable() {
                $.ajax({
                    url: empleadosDataRoute,
                    type: 'POST',
                    dataType: 'json',
                    headers: {
                        'X-CSRF-TOKEN': CsrfToken
                    },
                    success: function(response) {
                        if (response.data) {
                            // console.log('Datos encontrados: \n ' + response.data);
                            initializeDataTable(response.data);
                        } else {
                            console.log('No se encontraron datos de Empleados.');
                        }
                    },
                    error: function(xhr, textStatus, error) {
                        console.log('Error al obtener los datos de Empleados: ' + error);
                    }
                });
            }

            function generateButtons(row) {
                var btnEdit =
                    '<button class="btn btn-xs btn-default text-primary mx-1 shadow" title="Edit" onclick="editarEmpleado(this)" data-empleado=\'' +
                    JSON.stringify(row) +
                    '\'><i class="fa fa-lg fa-fw fa-pen"></i></button> ';
                var btnDelete =
                    '<button class="btn btn-xs btn-default text-danger mx-1 shadow" title="Delete" onclick="eliminarEmpleado(' +
                    row.id +
                    ')"><i class="fa fa-lg fa-fw fa-trash"></i></button> ';

                return '<nobr>' + btnEdit + btnDelete + '</nobr>';
            }

            refreshEmpleadoDataTable();

            setInterval(refreshEmpleadoDataTable, 10000);
        });
    </script>
@endsection
