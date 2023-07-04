@extends('adminlte::page')

{{-- @section('plugins.Datatables', true)
@section('plugins.jquery-validation', true)
@section('plugins.DatatablesPlugins', true)
@section('plugins.TempusDominusBs4', true)
@section('plugins.Select2', true) --}}

@section('content_header')
    <h1 class="m-0 text-dark">Tipos de Contrato</h1>
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

    <x-adminlte-card title="Lista de Tipos de Contrato" theme="pink" icon="fas fa-tags" class="elevation-3" maximizable>
        <x-datatable :columns=$columns :data=$data id="tiposContratoTable" />
    </x-adminlte-card>


    {{-- Modales --}}
    <x-registermodal :route="route('tipo-contratos.store')" :fields="[
        [
            'name' => 'i_tipo_contrato',
            'label' => 'Tipo de contrato',
            'placeholder' => 'Ingrese el tipo de contrato',
            'type' => 'input',
        ],
        [
            'name' => 'i_plazo',
            'label' => 'Plazo',
            'placeholder' => 'Ingrese el plazo en meses',
            'type' => 'number',
        ],
    ]" title="Registrar Tipo de contrato" size="md"
        modalId="registrarTipoModal" />


    <x-editmodal :route="route('tipo-contratos.update', ['id' => $tipoContratoId])" :fields="[
        [
            'name' => 'e_tipo_contrato',
            'label' => 'Tipo de contrato',
            'placeholder' => 'Ingrese el tipo de contrato',
            'type' => 'input',
        ],
        ['name' => 'e_plazo', 'label' => '', 'placeholder' => 'Ingrese el tipo de contrato', 'type' => 'number'],
        ['name' => 'e_id', 'type' => 'hidden'],
    ]" title='Editar Tipo' size='md' modalId='editarTipoModal'
        route_id="editarFormulario" />


    <x-deletemodal title='Editar Tipo' size='md' modalId='eliminarTipoModal' :route="route('tipo-contratos.update', ['id' => $tipoContratoId])"
        quetion='¿Está seguro que desa eliminar el tipo?' :field="['name' => 'd_id']" route_id="eliminarFormulario" />

@endsection

@section('js')
    <script>
        var tipoContratoId = 0;
        var tiposDataRoute = '{{ route('tipo-contratos.data') }}';
        var tipoCsrfToken = '{{ csrf_token() }}';
    </script>
    <script>
        function registrarTipoContrato() {
            // Lógica para registrar una Tipo
            $('#registrarTipoModal').modal('show');
        }

        function editarTipoContrato(button) {
            var tipo_contrato = JSON.parse(button.getAttribute(
                'data-tipo-contrato')); // Analizar la cadena JSON en un objeto

            // Asignar los valores a los campos del modal
            tipoContratoId = tipo_contrato.id;
            $('#editarTipoModal input[name="e_id"]').val(tipo_contrato.id);
            $('#editarTipoModal input[name="e_tipo_contrato"]').val(tipo_contrato['tipo de contrato']);
            $('#editarTipoModal input[name="e_plazo"]').val(tipo_contrato.plazo);

            // Actualizar el atributo 'route' del componente EditModal con la nueva ruta
            var route = '{{ route('tipo-contratos.update', ['id' => ':id']) }}'
                .replace(':id', tipo_contrato.id);
            $('#editarFormulario').attr('action', route);

            $('#editarTipoModal').modal('show'); // Invocar al modal de edición
        }

        function eliminarTipoContrato(id) {
            // Lógica para mostrar el mensaje de confirmación de eliminación
            var formId = 'eliminarFormulario';
            var route = '{{ route('tipo-contratos.destroy', ['id' => ':id']) }}'.replace(':id', id);
            $('#' + formId).attr('action', route);
            // y abrir el modal de eliminacións
            $('#eliminarTipoModal').modal('show');
        }

        $(function() {
            /**/
            var table = $('#tiposContratoTable').DataTable({
                columns: [{
                        data: 'id',
                        name: 'id',
                        visible: false,
                    },
                    {
                        data: 'tipo de contrato',
                        name: 'tipo de contrato'
                    },
                    {
                        data: 'plazo',
                        name: 'plazo'
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
                        text: '<i class="fa fa-plus"></i> Registrar Tipo',
                        className: 'btn btn-sm btn-primary bg-danger mx-1',
                        action: () => registrarTipoContrato(),
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

            function refreshTipoDataTable() {
                $.ajax({
                    url: tiposDataRoute,
                    type: 'POST',
                    dataType: 'json',
                    headers: {
                        'X-CSRF-TOKEN': tipoCsrfToken
                    },
                    success: function(response) {
                        if (response.data) {
                            // console.log('Datos encontrados: \n ' + response.data);
                            initializeDataTable(response.data);
                        } else {
                            console.log('No se encontraron datos de Tipos de Contrato.');
                        }
                    },
                    error: function(xhr, textStatus, error) {
                        console.log('Error al obtener los datos de Tipos de Contrato: ' + error);
                    }
                });
            }

            function generateButtons(row) {
                var btnEdit =
                    '<button class="btn btn-xs btn-default text-primary mx-1 shadow" title="Edit" onclick="editarTipoContrato(this)" data-tipo-contrato=\'' +
                    JSON.stringify(row) +
                    '\'><i class="fa fa-lg fa-fw fa-pen"></i></button> ';
                var btnDelete =
                    '<button class="btn btn-xs btn-default text-danger mx-1 shadow" title="Delete" onclick="eliminarTipoContrato(' +
                    row.id +
                    ')"><i class="fa fa-lg fa-fw fa-trash"></i></button> ';

                return '<nobr>' + btnEdit + btnDelete + '</nobr>';
            }

            refreshTipoDataTable();

            setInterval(refreshTipoDataTable, 10000);
        });
    </script>
@endsection
