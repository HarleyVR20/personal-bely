@extends('adminlte::page')

{{-- @section('plugins.Datatables', true)
@section('plugins.jquery-validation', true)
@section('plugins.DatatablesPlugins', true)
@section('plugins.TempusDominusBs4', true)
@section('plugins.Select2', true) --}}

@section('content_header')
    <h1 class="m-0 text-dark">Tipos</h1>
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

    <x-adminlte-card title="Lista de Tipos" theme="pink" icon="fas fa-tags" class="elevation-3" maximizable>
        <x-datatable :columns=$columns :data=$data id="tablaTiposRecorte" />
    </x-adminlte-card>


    {{-- Modales --}}
    <x-registermodal :route="route('tipo-recortes.store')" :fields="[
        [
            'name' => 'i_tipo',
            'label' => 'Tipo',
            'options' => [
                ['value' => '1', 'label' => 'Bonificación', 'selected' => 'true'],
                ['value' => '0', 'label' => 'Recorte'],
            ],
            'type' => 'radio',
        ],
        [
            'name' => 'i_descripcion',
            'label' => 'Descripcion',
            'placeholder' => 'Ingrese la descripcion',
            'type' => 'input',
        ],
    ]" title="Registrar tipo de recorte" size="md"
        modalId="registrarTipoRecorteModal" />


    <x-editmodal :route="route('tipo-recortes.update', ['id' => $tipoRecorteId])" :fields="[
        [
            'name' => 'e_tipo',
            'label' => 'Tipo',
            'options' => [
                ['value' => '1', 'label' => 'Bonificación', 'selected' => 'true'],
                ['value' => '0', 'label' => 'Recorte'],
            ],
            'type' => 'radio',
        ],
        [
            'name' => 'e_descripcion',
            'label' => 'Descripcion',
            'placeholder' => 'Ingrese la descripcion',
            'type' => 'input',
        ],
        ['name' => 'e_id', 'type' => 'hidden'],
    ]" title='Editar Tipo' size='md' modalId='editarTipoRecorteModal'
        route_id="editarFormulario" />


    <x-deletemodal title='Editar Tipo' size='md' modalId='eliminarTipoRecorteModal' :route="route('tipo-recortes.update', ['id' => $tipoRecorteId])"
        quetion='¿Está seguro que desa eliminar el tipo?' :field="['name' => 'd_id']" route_id="eliminarFormulario" />

@endsection

@section('js')
    <script>
        var tiposRecorteDataRoute = '{{ route('tipo-recortes.data') }}';
        var csrfToken = '{{ csrf_token() }}';
    </script>
    <script>
        function registrarTipoRecorte() {
            // Lógica para registrar una Tipo
            $('#registrarTipoRecorteModal').modal('show');
        }

        function editarTipoRecorte(button) {
            var tiporecorte = JSON.parse(button.getAttribute('data-tipo-recorte')); // Analizar la cadena JSON en un objeto

            // Asignar los valores a los campos del modal
            $('#editarTipoRecorteModal input[name="e_id"]').val(tiporecorte.id);
            $('#updateTipoRecorteForm input[name="e_tipo"][value="1"]').prop('checked', tiporecorte.tipo === 1);
            $('#updateTipoRecorteForm input[name="e_tipo"][value="0"]').prop('checked', tiporecorte.tipo ===
                0);
            $('#editarTipoRecorteModal input[name="e_descripcion"]').val(tiporecorte['descripcion']);

            // Actualizar el atributo 'route' del componente EditModal con la nueva ruta
            var route = '{{ route('tipo-recortes.update', ['id' => ':id']) }}'
                .replace(':id', tiporecorte.id);
            $('#editarFormulario').attr('action', route);

            $('#editarTipoRecorteModal').modal('show'); // Invocar al modal de edición
        }

        function eliminarTipoRecorte(id) {
            // Lógica para mostrar el mensaje de confirmación de eliminación
            var formId = 'eliminarFormulario';
            var route = '{{ route('tipo-recortes.destroy', ['id' => ':id']) }}'
                .replace(':id', id);
            $('#' + formId).attr('action', route);
            // y abrir el modal de eliminacións
            $('#eliminarTipoRecorteModal').modal('show');
        }

        $(function() {

            var table = $('#tablaTiposRecorte').DataTable({
                columns: [{
                        data: 'id',
                        name: 'id',
                        visible: false,
                    },
                    {
                        data: 'tipo',
                        name: 'tipo'
                    },
                    {
                        data: 'descripcion',
                        name: 'descripcion',
                    },
                    {
                        data: 'creado en',
                        name: 'creado en',
                    },
                    {
                        data: 'actualizado en',
                        name: 'actualizado en',
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
                        text: '<i class="fa fa-plus"></i> Registrar Tipo',
                        className: 'btn btn-sm btn-primary bg-danger mx-1',
                        action: () => registrarTipoRecorte(),
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
                    selector: 'td:first-child',
                    className: 'selected', // Agregar esta línea para aplicar la clase 'selected' a las filas seleccionadas
                },
            });
            table.on('select', function(e, dt, type, indexes) {

                var selectedRows = table.rows({
                    selected: true
                }).data();

                console.log(selectedRows);
                // Realizar acciones con las filas seleccionadas
            });

            table.on('deselect', function(e, dt, type, indexes) {
                var deselectedRows = table.rows(indexes).data();
                // Realizar acciones con las filas deseleccionadas
            });

            function initializeDataTable(data) {
                table.clear().rows.add(data).draw();
            }

            function refreshTipoDataTable() {
                $.ajax({
                    url: tiposRecorteDataRoute,
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
                            console.log('No se encontraron datos de Tipos de Recorte.');
                        }
                    },
                    error: function(xhr, textStatus, error) {
                        console.log('Error al obtener los datos de Tipos de Recorte: ' + error);
                    }
                });
            }

            function generateButtons(row) {
                var btnEdit =
                    '<button class="btn btn-xs btn-default text-primary mx-1 shadow" title="Edit" onclick="editarTipoRecorte(this)" data-tipo-recorte=\'' +
                    JSON.stringify(row) +
                    '\'><i class="fa fa-lg fa-fw fa-pen"></i></button> ';
                var btnDelete =
                    '<button class="btn btn-xs btn-default text-danger mx-1 shadow" title="Delete" onclick="eliminarTipoRecorte(' +
                    row.id +
                    ')"><i class="fa fa-lg fa-fw fa-trash"></i></button> ';

                return '<nobr>' + btnEdit + btnDelete + '</nobr>';
            }

            refreshTipoDataTable();

            setInterval(refreshTipoDataTable, 10000);
        });
    </script>
@endsection
