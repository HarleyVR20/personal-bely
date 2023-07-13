@extends('adminlte::page')

@section('content_header')
    <h1 class="m-0 text-dark">Areas</h1>
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


    <x-adminlte-card title="Lista de Areas" theme="pink" icon="fas fa-tags" class="elevation-3" maximizable>
        <x-datatable :columns=$columns :data=$data id="areasTable" />
    </x-adminlte-card>

    {{-- Modales --}}
    <x-registermodal :route="route('areas.store')" :fields="[
        ['name' => 'i_gerencia', 'label' => 'Gerencia', 'placeholder' => 'Ingrese la gerencia', 'type' => 'input'],
        ['name' => 'i_sub_area', 'label' => 'Sub area', 'placeholder' => 'Ingrese el área', 'type' => 'input'],
    ]" title="Registrar Área" size="md"
        modalId="registrarAreaModal" />


    <x-editmodal :route="route('areas.update', ['id' => $areaId])" :fields="[
        ['name' => 'e_gerencia', 'label' => 'Gerencia', 'placeholder' => 'Ingrese la gerencia', 'type' => 'input'],
        ['name' => 'e_sub_area', 'label' => 'Sub area', 'placeholder' => 'Ingrese la sub area', 'type' => 'input'],
        ['name' => 'e_id', 'type' => 'hidden'],
    ]" title='Editar Área' size='md' modalId='editarAreaModal'
        route_id="editarFormulario" />

    <x-deletemodal title='Eliminar Área' size='md' modalId='eliminarAreaModal' route_id="eliminarFormulario"
        :route="route('areas.destroy', ['id' => $areaId])" quetion='¿Está seguro que desea eliminar el área?' />

    <x-register-excel-modal title='Importar areas' modalId='registrarAreaExcelModal' :field="[
        'name' => 'excel_file',
        'label' => 'Seleccionar archivo Excel',
        'placeholder' => 'Seleccione un archivo',
    ]" :route="route('areas.import')" />

@endsection

@section('js')
    <script>
        // Array de objetos que define los modales y sus acciones asociadas
        var openModals = [{
            'name': 'registrarArea',
            'onClick': () => {
                $('#registrarAreaModal').modal('show'); // Invocar al modal de registro
            },
        }, {
            'name': 'registrarAreasExcel',
            'onClick': () => {
                $('#registrarAreaExcelModal').modal('show'); // Invocar al modal de registro por excel
            },
        }, {
            'name': 'editarAreas',
            'onClick': (button) => {
                var area = JSON.parse(button.getAttribute('data-area')); // Analizar la cadena JSON en un objeto

                // Asignar los valores a los campos del modal de edición
                $('#editarAreaModal input[name="e_id"]').val(area.id);
                $('#editarAreaModal input[name="e_gerencia"]').val(area.gerencia);
                $('#editarAreaModal input[name="e_sub_area"]').val(area['sub area']);

                // Actualizar el atributo 'route' del componente EditModal con la nueva ruta
                var route = '{{ route('areas.update', ['id' => ':id']) }}'
                    .replace(':id', area.id);
                $('#editarFormulario').attr('action', route);
                $('#editarAreaModal').modal('show'); // Invocar al modal de edición
            },
        }, {
            'name': 'eliminarAreas',
            'onClick': (id) => {
                var formId = 'eliminarFormulario';
                var route = '{{ route('areas.destroy', ['id' => ':id']) }}'
                    .replace(':id', id);
                $('#' + formId).attr('action', route);

                // Lógica para mostrar el mensaje de confirmación de eliminación
                // y abrir el modal de eliminación
                $('#eliminarAreaModal').modal('show'); // Invocar al modal de eliminación
            },
        }];

        // Función para buscar un objeto modal por su nombre
        function buscarOpcion(modalName) {
            return openModals.find(function(modal) {
                return modal.name === modalName;
            });
        }
    </script>
    <script>
        // Ruta para obtener los datos de los contratos
        var areasDataRoute = '{{ route('areas.data') }}';

        // Token CSRF para enviar con las solicitudes AJAX
        var csrfToken = '{{ csrf_token() }}';

        function generateButtons(row) {
            var btnEdit =
                '<button class="btn btn-xs btn-default text-primary mx-1 shadow" title="Edit" onclick="buscarOpcion(\'' +
                'editarAreas' + '\').onClick(this)" data-area = \'' + JSON
                .stringify(row) +
                '\'><i class="fa fa-lg fa-fw fa-pen"></i></button> ';
            var btnDelete =
                '<button class="btn btn-xs btn-default text-danger mx-1 shadow" title="Delete" onclick="buscarOpcion(\'' +
                'eliminarAreas' + '\').onClick(' +
                row.id + ')"><i class="fa fa-lg fa-fw fa-trash"></i></button> ';

            return '<nobr>' + btnEdit + btnDelete + '</nobr>';
        }

        function listar() {
            var table = $('#areasTable').DataTable({
                columns: [
                    // Definición de columnas del DataTable
                    {
                        data: 'id',
                        name: 'id',
                        visible: false,
                    },
                    {
                        data: 'gerencia',
                        name: 'gerencia',
                    },
                    {
                        data: 'sub area',
                        name: 'sub area',
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
                buttons: [
                    // Definición de botones del DataTable
                    {
                        extend: 'copy',
                        text: '<i class="fas fa-sticky-note text-yellow"></i>', // Copiar
                        className: 'btn btn-sm btn-default',
                    },
                    {
                        extend: 'csv',
                        text: '<i class="fas fa-file-csv text-blue"></i>', // CSV
                        className: 'btn btn-sm btn-default',
                    },
                    {
                        extend: 'excel',
                        text: '<i class="fas fa-file-excel text-green"></i>', // Excel
                        className: 'btn btn-sm btn-default',
                    },
                    {
                        extend: 'pdf',
                        text: '<i class="fas fa-file-pdf text-red"></i>', // PDF
                        className: 'btn btn-sm btn-default',
                    },
                    {
                        extend: 'print',
                        text: '<i class="fa fa-print"></i>',
                        className: 'btn btn-sm btn-default',
                    },
                    {
                        text: '<i class="fa fa-plus"></i> Registrar área',
                        className: 'btn btn-sm btn-primary bg-danger mx-1',
                        action: () => buscarOpcion('registrarArea').onClick(),
                    },
                    {
                        text: '<i class="fa fa-list"></i> Cargar áreas',
                        className: 'btn btn-sm btn-primary bg-danger mx-1',
                        action: () => buscarOpcion('registrarAreasExcel').onClick(),
                    },
                    {
                        extend: 'colvis',
                    },
                ],
                responsive: true,
                paging: true,
                stateDuration: -1,
                info: true,
                colReorder: true,
                select: {
                    style: 'multi',
                    selector: 'td:first-child'
                },
                ajax: {
                    url: areasDataRoute,
                    type: 'POST',
                    dataType: 'json',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                    },
                    data: 'data',
                    success: function(response) {
                        if (response.data) {
                            table.clear().rows.add(response.data).draw();
                        } else {
                            console.log('No se encontraron datos de Areas.');
                        }

                    },
                    error: function(xhr, textStatus, error) {
                        console.log('Error al obtener los datos de Areas: ' + error);
                    },
                    complete: function() {
                        // Llama a la función nuevamente después de 10 segundos
                        setTimeout(function() {
                            table.ajax.reload(null, false);
                        }, 10000);
                    }
                }
            });

            table.ajax.reload(null, false);
        }

        $(function() {
            listar();
        });
    </script>

@endsection
