@extends('adminlte::page')

{{-- @section('plugins.Datatables', true)
@section('plugins.jquery-validation', true)
@section('plugins.DatatablesPlugins', true)
@section('plugins.TempusDominusBs4', true)
@section('plugins.Select2', true) --}}

@section('content_header')
    <h1 class="m-0 text-dark">Recortes y Bonificaciones</h1>
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

    {{-- Tabla de recortes --}}
    <x-adminlte-card title="" theme="pink" icon="fas fa-tags" class="elevation-3" maximizable>
        <x-datatable :columns=$columns :data=$data id="recortesTable" />
    </x-adminlte-card>

    {{-- Modales --}}
    <x-registermodal :route="route('recortes.store')" :fields="[
        [
            'name' => 'i_selectTipoRecorte',
            'label' => 'Bonificaciones o recortes',
            'placeholder' => 'Seleccionar el tipo de recorte',
            'type' => 'select2_with_search',
        ],
        [
            'name' => 'i_monto_recortado',
            'label' => 'Monto',
            'placeholder' => 'Ingrese el monto por recorte',
            'type' => 'number',
        ],
        [
            'name' => 'i_observaciones',
            'label' => 'Observaciones',
            'placeholder' => 'Ingrese las observaciones',
            'type' => 'long_text',
        ],
    ]" title="Registrar Recorte" size="md"
        modalId="registarRecorteModal" />


    <x-editmodal :route="route('recortes.update', ['id' => $recorteId])" :fields="[
        [
            'name' => 'e_selectTipoRecorte',
            'label' => 'Bonificaciones o recortes',
            'placeholder' => 'Seleccionar el tipo',
            'type' => 'select2_with_search',
        ],
        [
            'name' => 'e_monto_recortado',
            'label' => 'Monto',
            'placeholder' => 'Ingrese el monto por recorte',
            'type' => 'number',
        ],
        [
            'name' => 'e_observaciones',
            'label' => 'Observaciones',
            'placeholder' => 'Ingrese las observaciones',
            'type' => 'long_text',
        ],
    ]" title='Editar Recorte' size='md' modalId='editarRecorteModal'
        route_id="editarFormulario" />


    <x-deletemodal title='Editar Recorte' size='md' modalId='eliminarRecorteModal' :route="route('recortes.update', ['id' => $recorteId])"
        quetion='¿Está seguro que desa eliminar el recorte?' :field="['name' => 'd_id']" route_id="eliminarFormulario" />

@endsection

@section('js')
    <script>
        var recortesDataRoute = '{{ route('recortes.data') }}';
        var tipoRecorteDataRoute = '{{ route('tipo-recortes.search') }}';
        var csrfToken = '{{ csrf_token() }}';
    </script>
    <script>
        function registrarRecorte() {
            // Lógica para registrar una Recorte
            $('#registarRecorteModal').modal('show');
        }

        function editarRecorte(button) {
            var recorte = JSON.parse(button.getAttribute('data-recorte')); // Analizar la cadena JSON en un objeto

            var selectValue_1 = recorte['tipo_id'];
            var selectText_1 = recorte['Tipo'];
            var optionTipoRecorte = new Option(selectText_1, selectValue_1, true, true); // Crear una opción
            // Asignar los valores a los campos del modal
            $('#editarRecorteModal select[name="e_selectTipoRecorte"]').empty().append(optionTipoRecorte);
            $('#editarRecorteModal input[name="e_monto_recortado"]').val(recorte['Monto']);
            $('#editarRecorteModal textarea[name="e_observaciones"]').text(recorte['Observaciones']);

            // Actualizar el atributo 'route' del componente EditModal con la nueva ruta
            var route = '{{ route('recortes.update', ['id' => ':id']) }}'
                .replace(':id', recorte.id);
            $('#editarFormulario').attr('action', route);

            $('#editarRecorteModal').modal('show'); // Invocar al modal de edición
        }

        function eliminarRecorte(id) {
            // Lógica para mostrar el mensaje de confirmación de eliminación
            // Actualiza el atributo route del c omponente DeleteModal con la nueva ruta
            var formId = 'eliminarFormulario';
            var route = '{{ route('recortes.destroy', ['id' => ':id']) }}'
                .replace(':id', id);
            $('#' + formId).attr('action', route);

            $('#eliminarRecorteModal').modal('show'); // Abrir el modal de eliminacións
        }

        $(function() {
            var table = $('#recortesTable').DataTable({
                columns: [{
                        data: 'id',
                        name: 'id',
                        visible: false,
                        orderable: false,
                        searchable: false,
                    },
                    {
                        data: 'tipo_id',
                        name: 'tipo_id',
                        visible: false,
                        orderable: false,
                        searchable: false,
                    },
                    {
                        data: 'Tipo',
                        name: 'Tipo',
                        // render: function(data, type, row) {
                        //     return '<span title="' + row.Tipo + '">' + data.split(' (')[0] +
                        //         '</span>';
                        // },
                    },
                    {
                        data: 'Monto',
                        name: 'Monto'
                    },
                    {
                        data: 'Observaciones',
                        name: 'Observaciones'
                    },
                    {
                        data: 'Creado en',
                        name: 'Creado en',
                        visible: false,
                    },
                    {
                        data: 'Actualizado en',
                        name: 'Actualizado en',
                        visible: false,
                    },
                    {
                        data: 'id',
                        name: 'Opciones',
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
                        text: '<i class="fa fa-plus"></i> Registrar Recorte',
                        className: 'btn btn-sm btn-primary bg-danger mx-1',
                        action: () => registrarRecorte(),
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

            function refreshRecorteDataTable() {
                $.ajax({
                    url: recortesDataRoute,
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
                            console.log('No se encontraron datos de Recortes.');
                        }
                    },
                    error: function(xhr, textStatus, error) {
                        console.log('Error al obtener los datos de Recortes: ' + error);
                    }
                });
            }

            function generateButtons(row) {
                var btnEdit =
                    '<button class="btn btn-xs btn-default text-primary mx-1 shadow" title="Edit" onclick="editarRecorte(this)" data-recorte=\'' +
                    JSON.stringify(row) +
                    '\'><i class="fa fa-lg fa-fw fa-pen"></i></button> ';
                var btnDelete =
                    '<button class="btn btn-xs btn-default text-danger mx-1 shadow" title="Delete" onclick="eliminarRecorte(' +
                    row.id +
                    ')"><i class="fa fa-lg fa-fw fa-trash"></i></button> ';

                return '<nobr>' + btnEdit + btnDelete + '</nobr>';
            }

            refreshRecorteDataTable();

            setInterval(refreshRecorteDataTable, 10000);
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
            initializeSelect2('#i_selectTipoRecorte', tipoRecorteDataRoute, 'q');
            initializeSelect2('#e_selectTipoRecorte', tipoRecorteDataRoute, 'q');
        });
    </script>
@endsection
