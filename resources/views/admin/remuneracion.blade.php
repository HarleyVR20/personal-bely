@extends('adminlte::page')

{{-- @section('plugins.Datatables', true)
@section('plugins.jquery-validation', true)
@section('plugins.DatatablesPlugins', true)
@section('plugins.TempusDominusBs4', true)
@section('plugins.Select2', true) --}}

@section('content_header')
    <h1 class="m-0 text-dark">Remuneracion</h1>
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

    <x-adminlte-card title="Lista de Remuneracion" theme="pink" icon="fas fa-tags" class="elevation-3" maximizable>
        <x-datatable :columns=$columns :data=$data id="remuneracionTable" />
    </x-adminlte-card>


    {{-- Modales --}}
    <x-registermodal :route="route('remuneraciones.store')" :fields="[
        [
            'name' => 'i_selectEmpleado',
            'label' => 'Empleado',
            'placeholder' => 'Seleccionar el empleado',
            'type' => 'select2_with_search',
        ],
        [
            'id' => 'i_selectTipoRecorte',
            'name' => 'i_selectTipoRecorte[]',
            'label' => 'Recorte o bonificación',
            'placeholder' => 'Seleccionar el tipo',
            'type' => 'select2_with_search',
            'isMultiple' => 'true',
        ],
        [
            'name' => 'i_selectContrato',
            'label' => 'Contrato',
            'placeholder' => 'Seleccionar el contrato',
            'type' => 'select2_with_search',
        ],
        [
            'name' => 'i_concepto',
            'label' => 'Concepto',
            'placeholder' => 'Ingrese conceptos',
            'type' => 'long_text',
        ],
    ]" title="Registrar Remuneracion" size="md"
        modalId="registrarRemuneracionModal" route_id="editarFormulario" />


    <x-editmodal :route="route('remuneraciones.update', ['id' => $remuneracionId])" :fields="[
        [
            'name' => 'e_selectEmpleado',
            'label' => 'Empleado',
            'placeholder' => 'Seleccionar el empleado',
            'type' => 'select2_with_search',
        ],
        [
            'id' => 'e_selectTipoRecorte',
            'name' => 'e_selectTipoRecorte[]',
            'label' => 'Recorte o bonificación',
            'placeholder' => 'Seleccionar el tipo',
            'type' => 'select2_with_search',
            'isMultiple' => 'true',
        ],
        [
            'name' => 'e_selectContrato',
            'label' => 'Contrato',
            'placeholder' => 'Seleccionar el contrato',
            'type' => 'select2_with_search',
        ],
        [
            'name' => 'e_concepto',
            'label' => 'Concepto',
            'placeholder' => 'Ingrese conceptos',
            'type' => 'long_text',
        ],
    ]" title='Editar Remuneracion' size='md'
        modalId='editarRemuneracionModal' route_id="editarFormulario" />


    <x-deletemodal title='Editar Remuneracion' size='md' modalId='eliminarRemuneracionModal' :route="route('remuneraciones.update', ['id' => $remuneracionId])"
        route_id="eliminarFormulario" quetion='¿Está seguro que desa eliminar el remuneracion?' />

@endsection

@section('js')
    <script>
        var remuneracionId = 0;
        var remuneracionesDataRoute = '{{ route('remuneraciones.data') }}';
        var empleadosDataRoute = '{{ route('empleados.search') }}';
        var contratosDataRoute = '{{ route('contratos.search') }}';
        var recortesDataRoute = '{{ route('recortes.search') }}';
        var csrfToken = '{{ csrf_token() }}';
    </script>
    <script>
        function registrarRemuneracion() {
            // Lógica para registrar una Remuneracion
            $('#registrarRemuneracionModal').modal('show');
        }

        function editarRemuneracion(button) {
            var remuneracion = JSON.parse(button.getAttribute('data-remuneracion')); // Analizar la cadena JSON en un objeto

            var selectValue1 = remuneracion['empleado_id'];
            var selectText1 = remuneracion['Empleado'];
            var optionEmpleado = new Option(selectText1, selectValue1, true, true); // Crear una opción

            var selectValue2 = remuneracion['Contrato_id'];
            var selectText2 = remuneracion['Contrato'];
            var optionContrato = new Option(selectText2, selectValue2, true, true); // Crear una opción

            // Asignar los valores a los campos del modal
            $('#editarRemuneracionModal select[name="e_selectEmpleado"]').empty().append(optionEmpleado);

            var selectElement = $('#editarRemuneracionModal select[name="e_selectTipoRecorte[]"]').empty();

            remuneracion['tipos_recorte'].ids.forEach(function(id, index) {
                var nombre = remuneracion['tipos_recorte'].nombres[id].description;
                var option = new Option(nombre, id, index === 0,
                    true); // Crear una opción y establecerla como seleccionada si es el primer elemento
                selectElement.append(option); // Agregar la opción al elemento select
            });

            $('#editarRemuneracionModal select[name="e_selectContrato"]').empty().append(optionContrato);
            $('#editarRemuneracionModal textarea[name="e_concepto"]').text(remuneracion['Concepto']);

            // Actualizar el atributo 'route' del componente EditModal con la nueva ruta
            var route = '{{ route('remuneraciones.update', ['id' => ':id']) }}'
                .replace(':id', remuneracion.id);
            $('#editarFormulario').attr('action', route);

            $('#editarRemuneracionModal').modal('show'); // Invocar al modal de edición
        }

        function eliminarRemuneracion(id) {
            // Lógica para mostrar el mensaje de confirmación de eliminación
            // Actualiza el atributo route del c omponente DeleteModal con la nueva ruta
            var formId = 'eliminarFormulario';
            var route = '{{ route('remuneraciones.destroy', ['id' => ':id']) }}'
                .replace(':id', id);
            $('#' + formId).attr('action', route);

            $('#eliminarRemuneracionModal').modal('show');
        }

        $(function() {

            var table = $('#remuneracionTable').DataTable({
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
                        data: 'Empleado',
                        name: 'Empleado'
                    },
                    {
                        data: 'tipos_recorte',
                        name: 'tipos_recorte',
                        visible: false,
                    },
                    {
                        data: 'Contrato_id',
                        name: 'Contrato_id',
                        visible: false,
                    },
                    {
                        data: 'Contrato',
                        name: 'Contrato'
                    },
                    {
                        data: 'Concepto',
                        name: 'Concepto'
                    },
                    {
                        data: 'Monto total',
                        name: 'Monto total'
                    },
                    {
                        data: 'creado en',
                        name: 'creado en',
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
                        text: '<i class="fa fa-plus"></i> Registrar Remuneracion',
                        className: 'btn btn-sm btn-primary bg-danger mx-1',
                        action: () => registrarRemuneracion(),
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

            function refreshRemuneracionDataTable() {
                $.ajax({
                    url: remuneracionesDataRoute,
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
                            console.log('No se encontraron datos de Remuneracion.');
                        }
                    },
                    error: function(xhr, textStatus, error) {
                        console.log('Error al obtener los datos de Remuneracion: ' + error);
                    }
                });
            }

            function generateButtons(row) {
                var btnEdit =
                    '<button class="btn btn-xs btn-default text-primary mx-1 shadow" title="Edit" onclick="editarRemuneracion(this)" data-remuneracion=\'' +
                    JSON.stringify(row) +
                    '\'><i class="fa fa-lg fa-fw fa-pen"></i></button> ';
                var btnDelete =
                    '<button class="btn btn-xs btn-default text-danger mx-1 shadow" title="Delete" onclick="eliminarRemuneracion(' +
                    row.id +
                    ')"><i class="fa fa-lg fa-fw fa-trash"></i></button> ';
                var btnDetails =
                    '<a class="btn btn-xs btn-default text-info mx-1 shadow" title="Details" href="{{ route('calendario', ['empleado' => '']) }}' +
                    row.empleado_id + '"><i class="fa fa-lg fa-fw fa-info-circle"></i></a> ';


                return '<nobr>' + btnEdit + btnDelete + btnDetails + '</nobr>';
            }

            refreshRemuneracionDataTable();

            setInterval(refreshRemuneracionDataTable, 10000);
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
            initializeSelect2('#i_selectContrato', contratosDataRoute, 'q');
            initializeSelect2('#e_selectContrato', contratosDataRoute, 'q');
            initializeSelect2('#i_selectTipoRecorte', recortesDataRoute, 'q');
            initializeSelect2('#e_selectTipoRecorte', recortesDataRoute, 'q');
        });
    </script>
@endsection
