@extends('adminlte::page')

@section('content_header')
    <h1 class="m-0 text-dark">Cargos</h1>
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

    <x-adminlte-card title="Lista de Cargos" theme="pink" icon="fas fa-tags" class="elevation-3" maximizable>
        <x-datatable :columns=$columns :data=$data id="tablaCargos" />
    </x-adminlte-card>


    {{-- Modales --}}
    <x-registermodal :route="route('cargos.store')" :fields="[
        [
            'name' => 'i_area',
            'label' => 'Área',
            'placeholder' => 'Seleccionar el área',
            'type' => 'select2_with_search',
        ],
        ['name' => 'i_cargo', 'label' => 'Cargo', 'placeholder' => 'Ingrese el cargo', 'type' => 'input'],
    ]" title="Registrar Cargos" size="md"
        modalId="registrarCargoModal" />


    <x-editmodal :route="route('cargos.update', ['id' => $cargoId])" :fields="[
        [
            'name' => 'e_area',
            'label' => 'Área',
            'placeholder' => 'Seleccionar el área',
            'type' => 'select2_with_search',
        ],
        ['name' => 'e_cargo', 'label' => 'Cargo', 'placeholder' => 'Ingrese el cargo', 'type' => 'input'],
        ['name' => 'e_id', 'type' => 'hidden'],
    ]" title='Editar Cargo' size='md' modalId='editarCargoModal'
        route_id="editarFormulario" />


    <x-deletemodal title='Editar Cargo' size='md' modalId='eliminarCargoModal' :route="route('cargos.update', ['id' => $cargoId])"
        quetion='¿Está seguro que desea eliminar el cargo?' :field="['name' => 'd_id']" route_id="eliminarFormulario" />

@endsection
@section('js')
    <script>
        // Definición de la ruta de datos y el token CSRF
        var cargoDataRoute = '{{ route('cargos.data') }}';
        var csrfToken = '{{ csrf_token() }}';
    </script>

    <script>
        // Definición de los modales y sus respectivas funciones
        var openModals = [{
            'name': 'registrarCargo',
            'onClick': () => {
                // Lógica para abrir el modal de registro de cargo
                $('#registrarCargoModal').modal('show');
            },
        }, {
            'name': 'registrarCargoExcel',
            'onClick': () => {
                // Lógica para abrir el modal de registro de cargo por Excel
                // $('#registrarAreaExcelModal').modal('show');
            },
        }, {
            'name': 'editarCargo',
            'onClick': (button) => {
                // Lógica para editar un cargo
                var cargo = JSON.parse(button.getAttribute('data-cargo'));

                // Asignar los valores a los campos del modal
                var selectValue_1 = cargo['area_id'];
                var selectText_1 = cargo['área'];
                var option_area = new Option(selectText_1, selectValue_1, true, true); // Crear una opción

                // Vaciar el select y agregar la opción creada
                $('#editarCargoModal select[name="e_area"]').empty().append(option_area);
                $('#editarCargoModal input[name="e_id"]').val(cargo.id);
                $('#editarCargoModal input[name="e_cargo"]').val(cargo.nombre);

                // Actualizar el atributo 'route' del componente EditModal con la nueva ruta
                var route = '{{ route('cargos.update', ['id' => ':id']) }}'.replace(':id', cargo.id);
                $('#editarFormulario').attr('action', route);

                $('#editarCargoModal').modal('show');
            },
        }, {
            'name': 'eliminarCargo',
            'onClick': (id) => {
                // Lógica para mostrar el mensaje de confirmación de eliminación
                var formId = 'eliminarFormulario';
                var route = '{{ route('cargos.destroy', ['id' => ':id']) }}'.replace(':id', id);
                $('#' + formId).attr('action', route);

                // Abrir el modal de eliminación
                $('#eliminarCargoModal').modal('show');
            },
        }];

        // Función para buscar un modal por su nombre
        function buscarOpcion(modalName) {
            return openModals.find(function(modal) {
                return modal.name === modalName;
            });
        }

        // Inicialización del DataTable
        $(function() {
            var table = $('#tablaCargos').DataTable({
                columns: [{
                        data: 'id',
                        name: 'id',
                        visible: false,
                        orderable: false,
                        searchable: false,
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
                        name: 'área'
                    },
                    {
                        data: 'nombre',
                        name: 'nombre'
                    },
                    {
                        data: 'creado en',
                        name: 'creado en'
                    },
                    {
                        data: 'actualizado en',
                        name: 'actualizado en'
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
                timeout: 5000,
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
                        text: '<i class="fas fa-sticky-note text-yellow"></i>',
                        className: 'btn btn-sm btn-default'
                    },
                    {
                        extend: 'csv',
                        text: '<i class="fas fa-file-csv text-blue"></i>',
                        className: 'btn btn-sm btn-default'
                    },
                    {
                        extend: 'excel',
                        text: '<i class="fas fa-file-excel text-green"></i>',
                        className: 'btn btn-sm btn-default'
                    },
                    {
                        extend: 'pdf',
                        text: '<i class="fas fa-file-pdf text-red"></i>',
                        className: 'btn btn-sm btn-default'
                    },
                    {
                        extend: 'print',
                        text: '<i class="fa fa-print"></i>',
                        className: 'btn btn-sm btn-default'
                    },
                    {
                        text: '<i class="fa fa-plus"></i> Registrar Cargo',
                        className: 'btn btn-sm btn-primary bg-danger mx-1',
                        action: () => buscarOpcion('registrarCargo').onClick(),
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

            // Función para inicializar el DataTable con los datos
            function initializeDataTable(data) {
                table.clear().rows.add(data).draw();
            }

            // Función para refrescar los datos del DataTable
            function refreshCargoDataTable() {
                $.ajax({
                    url: cargoDataRoute,
                    type: 'POST',
                    dataType: 'json',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken
                    },
                    success: function(response) {
                        if (response.data) {
                            initializeDataTable(response.data);
                        } else {
                            console.log('No se encontraron datos de Cargos.');
                        }
                    },
                    error: function(xhr, textStatus, error) {
                        console.log('Error al obtener los datos de Cargos: ' + error);
                    }
                });
            }

            // Función para generar los botones de acción en la tabla
            function generateButtons(row) {
                var btnEdit =
                    '<button class="btn btn-xs btn-default text-primary mx-1 shadow" title="Edit" onclick="buscarOpcion(\'editarCargo\').onClick(this)" data-cargo=\'' +
                    JSON.stringify(row) + '\'><i class="fa fa-lg fa-fw fa-pen"></i></button> ';
                var btnDelete =
                    '<button class="btn btn-xs btn-default text-danger mx-1 shadow" title="Delete" onclick="buscarOpcion(\'eliminarCargo\').onClick(' +
                    row.id + ')"><i class="fa fa-lg fa-fw fa-trash"></i></button> ';

                return '<nobr>' + btnEdit + btnDelete + '</nobr>';
            }

            // Inicializar el DataTable y refrescar los datos
            refreshCargoDataTable();

            // Actualizar los datos del DataTable cada 10 segundos
            setInterval(refreshCargoDataTable, 10000);
        });

        // Variables para el manejo de las áreas
        var areaDataRoute = '{{ route('areas.search') }}';

        // Función para inicializar el selector de áreas
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
                            results: data
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

        // Inicializar los selectores de áreas
        $(function() {
            initializeSelect2('#i_area', areaDataRoute, 'q');
            initializeSelect2('#e_area', areaDataRoute, 'q');
        });
    </script>
@endsection
