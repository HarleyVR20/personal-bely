@extends('adminlte::page')

{{-- @section('plugins.Datatables', true)
@section('plugins.jquery-validation', false)
@section('plugins.DatatablesPlugins', true)
@section('plugins.TempusDominusBs4', true)
@section('plugins.Select2', true) --}}

@section('content_header')
    <h1 class="m-0 text-dark">Contratos</h1>
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

    <x-adminlte-card title="Lista de Contratos" theme="pink" icon="fas fa-tags" class="elevation-3" maximizable>
        <x-datatable :columns=$columns :data=$data id="tablaContratos" />
    </x-adminlte-card>

    {{-- Modales --}}
    <x-registermodal :route="route('contratos.store')" :fields="[
        [
            'name' => 'i_selectEmpleado',
            'label' => 'Empleado',
            'placeholder' => 'Seleccionar el empleado',
            'type' => 'select2_with_search',
        ],
        [
            'name' => 'i_selectTipoContrato',
            'label' => 'Tipo de contrato',
            'placeholder' => 'Seleccionar tipo de contrato',
            'type' => 'select2_with_search',
        ],
        [
            'name' => 'i_selectModalidad',
            'label' => 'Modalidad',
            'placeholder' => 'Seleccionar la modalidad',
            'type' => 'select2_with_search',
        ],
        [
            'name' => 'i_fvinculacion',
            'label' => 'fecha de vinculación',
            'placeholder' => 'Ingrese la fecha de vinculación',
            'title' => 'fecha de vinculación',
            'config' => 'only_date',
            'type' => 'datetime',
        ],
        [
            'name' => 'i_fretiro',
            'label' => 'fecha de retiro',
            'placeholder' => 'Ingrese la fecha de retiro',
            'title' => 'fecha de retiro',
            'config' => 'only_date',
            'type' => 'datetime',
        ],
        [
            'id' => 'i_dias_laborables',
            'name' => 'i_dias_laborables[]',
            'label' => 'Dias laborables',
            'placeholder' => 'Días laborables',
            'type' => 'select',
        ],
        [
            'name' => 'i_horario_entrada',
            'label' => 'Horario de entrada',
            'placeholder' => 'Ingrese la fecha de entrada',
            'title' => 'Fecha entrada',
            'config' => 'only_hour',
            'type' => 'datetime',
        ],
        [
            'name' => 'i_horario_salida',
            'label' => 'Horario de salida',
            'placeholder' => 'Ingrese la fecha de salida',
            'title' => 'Fecha salida',
            'config' => 'only_hour',
            'type' => 'datetime',
        ],
        [
            'name' => 'i_salario_base',
            'label' => 'Salario base',
            'placeholder' => 'Ingrese el salario base',
            'type' => 'input',
        ],
        [
            'name' => 'i_marco_legal',
            'label' => 'Marco legal',
            'placeholder' => 'Ingrese el marco legal',
            'type' => 'long_text',
        ],
        [
            'name' => 'i_observacion',
            'label' => 'Observacion',
            'placeholder' => 'Ingrese las observaciones necesarias',
            'type' => 'long_text',
        ],
    ]" title="Registrar Contrato" size="md"
        modalId="registrarContratoModal" />


    <x-editmodal :route="route('contratos.update', ['id' => $contratoId])" :fields="[
        [
            'name' => 'e_selectEmpleado',
            'label' => 'Empleado',
            'placeholder' => 'Seleccionar el empleado',
            'type' => 'select2_with_search',
        ],
        [
            'name' => 'e_selectTipoContrato',
            'label' => 'Tipo de contrato',
            'placeholder' => 'Seleccionar tipo de contrato',
            'type' => 'select2_with_search',
        ],
        [
            'name' => 'e_selectModalidad',
            'label' => 'Modalidad',
            'placeholder' => 'Seleccionar la modalidad',
            'type' => 'select2_with_search',
        ],
        [
            'name' => 'e_fvinculacion',
            'label' => 'fecha de vinculación',
            'placeholder' => 'Ingrese la fecha de vinculación',
            'title' => 'fecha de vinculación',
            'config' => 'only_date',
            'type' => 'datetime',
        ],
        [
            'name' => 'e_fretiro',
            'label' => 'fecha de retiro',
            'placeholder' => 'Ingrese la fecha de retiro',
            'title' => 'fecha de retiro',
            'config' => 'only_date',
            'type' => 'datetime',
        ],
        [
            'id' => 'e_dias_laborables',
            'name' => 'e_dias_laborables[]',
            'label' => 'Dias laborables',
            'placeholder' => 'Días laborables',
            'type' => 'select',
        ],
        [
            'name' => 'e_horario_entrada',
            'label' => 'Horario de entrada',
            'placeholder' => 'Ingrese la fecha de entrada',
            'title' => 'Fecha entrada',
            'config' => 'only_hour',
            'type' => 'datetime',
        ],
        [
            'name' => 'e_horario_salida',
            'label' => 'Horario de salida',
            'placeholder' => 'Ingrese la fecha de salida',
            'title' => 'Fecha salida',
            'config' => 'only_hour',
            'type' => 'datetime',
        ],
        [
            'name' => 'e_salario_base',
            'label' => 'Salario base',
            'placeholder' => 'Ingrese el nombre',
            'type' => 'input',
        ],
        [
            'name' => 'e_marco_legal',
            'label' => 'Marco legal',
            'placeholder' => 'Ingrese el marco legal',
            'type' => 'long_text',
        ],
        [
            'name' => 'e_observacion',
            'label' => 'Observacion',
            'placeholder' => 'Ingrese las observaciones necesarias',
            'type' => 'long_text',
        ],
    ]" title='Editar Contrato' size='md' modalId='editarContratoModal'
        route_id="editarFormulario" />


    <x-deletemodal title='Editar Contrato' size='md' modalId='eliminarContratoModal' :route="route('contratos.destroy', ['id' => $contratoId])"
        quetion='¿Está seguro que desa eliminar el contrato?' :field="['name' => 'd_id']" route_id="eliminarFormulario" />

@endsection

@section('js')
    <script>
        var contratosDataRoute = '{{ route('contratos.data') }}';
        var empleadosDataRoute = '{{ route('empleados.search') }}';
        var tipoContratoDataRoute = '{{ route('tipo-contratos.search') }}';
        var modalidadDataRoute = '{{ route('modalidades.search') }}';
        var csrfToken = '{{ csrf_token() }}';
    </script>
    <script>
        function registrarContrato() {
            // Lógica para registrar una Producto
            $('#registrarContratoModal').modal('show');
        }

        function editarContrato(button) {
            var contrato = JSON.parse(button.getAttribute('data-contrato')); // Analizar la cadena JSON en un objeto

            var selectValue = contrato['empleado_id'];
            var selectText = contrato['empleado'];
            var optionEmpleado = new Option(selectText, selectValue, true, true); // Crear una opción

            var selectValue = contrato['tipoContrato_id'];
            var selectText = contrato['tipo de contrato'];
            var optionTContrato = new Option(selectText, selectValue, true, true); // Crear una opción

            var selectValue = contrato['modalidad_id'];
            var selectText = contrato['modalidad'];
            var optionModalidad = new Option(selectText, selectValue, true, true); // Crear una opción


            var diasLaborables = contrato['dias laborables'].split(','); // Separar los días en un array
            var selectElement = $('#editarContratoModal select[name="e_dias_laborables[]"]').empty();

            diasLaborables.forEach(function(dia) {
                var option = new Option(dia.trim(), dia.trim(), true,
                    true); // Crear una opción y establecerla como seleccionada
                selectElement.append(option); // Agregar la opción al elemento select
            });

            // Asignar los valores a los campos del modal
            $('#editarContratoModal input[name="e_id"]').val(contrato.id);
            $('#editarContratoModal select[name="e_selectEmpleado"]').empty().append(optionEmpleado);
            $('#editarContratoModal select[name="e_selectTipoContrato"]').empty().append(optionTContrato);
            $('#editarContratoModal select[name="e_selectModalidad"]').empty().append(optionModalidad);
            $('#editarContratoModal textarea[name="e_marco_legal"]').text(contrato['marco legal']);
            $('#editarContratoModal textarea[name="e_observacion"]').text(contrato['observacion']);
            $('#editarContratoModal input[name="e_fvinculacion"]').val(contrato['fecha de vinculación']);
            $('#editarContratoModal input[name="e_fretiro"]').val(contrato['fecha de retiro']);
            $('#editarContratoModal input[name="e_horario_entrada"]').val(contrato['horario de entrada']);
            $('#editarContratoModal input[name="e_horario_salida"]').val(contrato['horario de salida']);
            $('#editarContratoModal input[name="e_salario_base"]').val(contrato['salario base']);

            // Actualizar el atributo 'route' del componente EditModal con la nueva ruta
            var route = '{{ route('contratos.update', ['id' => ':id']) }}'
                .replace(':id', contrato.id);
            $('#editarFormulario').attr('action', route);

            $('#editarContratoModal').modal('show'); // Invocar al modal de edición
        }

        function eliminarContrato(id) {
            // Lógica para mostrar el mensaje de confirmación de eliminación
            var formId = 'eliminarFormulario';
            var route = '{{ route('contratos.destroy', ['id' => ':id']) }}'
                .replace(':id', id);
            $('#' + formId).attr('action', route);
            // y abrir el modal de eliminacións
            $('#eliminarContratoModal').modal('show');
        }

        $(function() {
            var table = $('#tablaContratos').DataTable({
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
                        data: 'tipoContrato_id',
                        name: 'tipoContrato_id',
                        visible: false,
                    },
                    {
                        data: 'tipo de contrato',
                        name: 'tipo de contrato'
                    },
                    {
                        data: 'modalidad_id',
                        name: 'modalidad_id',
                        visible: false,
                    },
                    {
                        data: 'modalidad',
                        name: 'modalidad'
                    },
                    {
                        data: 'fecha de vinculación',
                        name: 'fecha de vinculación',
                    },
                    {
                        data: 'fecha de retiro',
                        name: 'fecha de retiro',
                    },
                    {
                        data: 'dias laborables',
                        name: 'dias laborables',
                    },
                    {
                        data: 'horario de entrada',
                        name: 'horario de entrada',
                    },
                    {
                        data: 'horario de salida',
                        name: 'horario de salida',
                    },
                    {
                        data: 'salario base',
                        name: 'salario base',
                    },
                    {
                        data: 'marco legal',
                        name: 'marco legal'
                    },
                    {
                        data: 'observacion',
                        name: 'observacion'
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
                        text: '<i class="fa fa-plus"></i> Registrar Contrato',
                        className: 'btn btn-sm btn-primary bg-danger mx-1',
                        action: () => registrarContrato(),
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

            function refreshContratoDataTable() {
                $.ajax({
                    url: contratosDataRoute,
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
                            console.log('No se encontraron datos de Contratos.');
                        }
                    },
                    error: function(xhr, textStatus, error) {
                        console.log('Error al obtener los datos de Contratos: ' + error);
                    }
                });
            }

            function generateButtons(row) {
                var btnEdit =
                    '<button class="btn btn-xs btn-default text-primary mx-1 shadow" title="Edit" onclick="editarContrato(this)" data-contrato=\'' +
                    JSON.stringify(row) +
                    '\'><i class="fa fa-lg fa-fw fa-pen"></i></button> ';
                var btnDelete =
                    '<button class="btn btn-xs btn-default text-danger mx-1 shadow" title="Delete" onclick="eliminarContrato(' +
                    row.id +
                    ')"><i class="fa fa-lg fa-fw fa-trash"></i></button> ';

                return '<nobr>' + btnEdit + btnDelete + '</nobr>';
            }

            refreshContratoDataTable();

            setInterval(refreshContratoDataTable, 10000);
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
            initializeSelect2('#i_selectModalidad', modalidadDataRoute, 'modd');
            initializeSelect2('#e_selectModalidad', modalidadDataRoute, 'modd');
            initializeSelect2('#i_selectTipoContrato', tipoContratoDataRoute, 'tipoCon');
            initializeSelect2('#e_selectTipoContrato', tipoContratoDataRoute, 'tipoCon');
        });
    </script>
@endsection
