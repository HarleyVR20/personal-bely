@extends('adminlte::page')

@section('content_header')
    <h1 class="m-0 text-dark">Usuarios</h1>
@stop

@php
    $columns = ['id', 'id_area', 'Nombre', 'área', 'Correo', 'creado en', 'actualizado en', 'opciones'];
    $data = [];
    $usuarioId = 0;
@endphp

@section('content')
    @if (count($errors) > 0)
        <div class="alert alert-danger">
            <strong>Ups!</strong> Hubo algunos problemas con tu entrada.<br><br>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if ($message = Session::get('success'))
        <div class="alert alert-success">
            {{ $message }}
        </div>
    @endif
    <x-adminlte-card title="Lista de Usuarios" theme="pink" icon="fas fa-tags" class="elevation-3" maximizable>
        <x-datatable :columns=$columns :data=$data id="usuariosTable" />
    </x-adminlte-card>

    {{-- Modales --}}
    <x-registermodal :route="route('register')" :fields="[
        [
            'name' => 'name',
            'label' => 'Nombre',
            'placeholder' => 'Ingrese nombre',
            'type' => 'input',
            'required' => 'true',
        ],
        [
            'name' => 'r_area_id',
            'label' => 'área',
            'placeholder' => 'Seleccionar el área',
            'type' => 'select2_with_search',
        ],
        [
            'name' => 'email',
            'label' => 'Correo electrónico',
            'placeholder' => 'Ingrese correo',
            'type' => 'input',
            'type_input' => 'email',
            'required' => 'true',
        ],
        [
            'name' => 'password',
            'label' => 'Contraseña',
            'placeholder' => 'Ingrese contraseña',
            'type' => 'input',
            'type_input' => 'password',
            'required' => 'true',
        ],
        [
            'name' => 'password_confirmation',
            'label' => 'Confirmar contraseña',
            'placeholder' => 'Ingresar contraseñá',
            'type' => 'input',
            'type_input' => 'password',
            'required' => 'true',
        ],
    ]" title="Registrar Usuario" size="md"
        modalId="registrarUsuarioModal" />


    <x-editmodal :route="route('user-profile-information.update')" :fields="[
        [
            'name' => 'user_id',
            'type' => 'hidden',
        ],
        [
            'name' => 'name',
            'label' => 'Nombre',
            'placeholder' => 'Ingrese nombre',
            'type' => 'input',
            'required' => 'true',
        ],
        [
            'name' => 'e_area_id',
            'label' => 'área',
            'placeholder' => 'Seleccionar el área',
            'type' => 'select2_with_search',
        ],
        [
            'name' => 'email',
            'label' => 'Correo electrónico',
            'placeholder' => 'Ingrese correo',
            'type' => 'input',
            'type_input' => 'email',
            'required' => 'true',
        ],
        ['name' => 'e_id', 'type' => 'hidden'],
    ]" title='Editar Usuario' size='md' modalId='editarUsuarioModal'
        route_id="editarFormulario" />


    <x-deletemodal title='Eliminar Usuario' size='md' modalId='eliminarUsuarioModal' :route="route('usuarios.destroy', ['id' => $usuarioId])"
        quetion='¿Está seguro que desea eliminar el usuario?' route_id='eliminarFormulario'/>

@endsection

@section('js')
    <script>
        var usuariosDataRoute = '{{ route('usuarios.data') }}';
        var areasDataRoute = '{{ route('areas.search') }}';
        var csrfToken = '{{ csrf_token() }}';
    </script>
    <script>
        function registrarUsuario() {
            // Lógica para registrar una Producto
            $('#registrarUsuarioModal').modal('show');
        }

        function editarUsuario(button) {
            var area = JSON.parse(button.getAttribute('data-area')); // Analizar la cadena JSON en un objeto

            // Asignar los valores a los campos del modal

            var selectValue = area['id_area'];
            var selectText = area['Área'];
            var areaOption = new Option(selectText, selectValue, true, true); // Crear una opción

            // Asignar los valores a los campos del modal
            $('#editarUsuarioModal select[name="e_area_id"]').empty().append(areaOption);
            $('#editarUsuarioModal input[name="user_id"]').val(area.id);
            $('#editarUsuarioModal input[name="name"]').val(decodeURIComponent(area.Nombre));
            $('#editarUsuarioModal input[name="email"]').val(area.Correo);

            // Actualizar el atributo 'route' del componente EditModal con la nueva ruta
            // var route = '{{ route('user-profile-information.update', ['id' => ':id']) }}'
            //     .replace(':id', area.id);
            // $('#editarFormulario').attr('action', route);

            $('#editarUsuarioModal').modal('show'); // Invocar al modal de edición
        }

        function eliminarUsuario(id) {
            // Lógica para mostrar el mensaje de confirmación de eliminación

            var formId = 'eliminarFormulario';
            var route = '{{ route('usuarios.destroy', ['id' => ':id']) }}'
                .replace(':id', id);
            $('#' + formId).attr('action', route);

            // y abrir el modal de eliminacións
            $('#eliminarUsuarioModal').modal('show');
        }

        $(function() {

            var table = $('#usuariosTable').DataTable({
                // 'id', 'Nombre', 'Usuario', 'Correo', 'creado en', 'actualizado en', 'opciones'
                columns: [{
                        data: 'id',
                        name: 'id',
                        visible: false,
                    },
                    {
                        data: 'id_area',
                        name: 'id_area',
                        visible: false,
                    },
                    {
                        data: 'Nombre',
                        name: 'Nombre',
                    },
                    {
                        data: 'área',
                        name: 'área',
                    },
                    {
                        data: 'Correo',
                        name: 'Correo',
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
                        text: '<i class="fa fa-plus"></i> Registrar Usuario',
                        className: 'btn btn-sm btn-primary bg-danger mx-1',
                        action: () => registrarUsuario(),
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

            function refreshAreaDataTable() {
                $.ajax({
                    url: usuariosDataRoute,
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
                            console.log('No se encontraron datos de Usuarios.');
                        }
                    },
                    error: function(xhr, textStatus, error) {
                        console.log('Error al obtener los datos de Usuarios: ' + error);
                    }
                });
            }

            function generateButtons(row) {
                function customEncodeURIComponent(value) {
                    return encodeURIComponent(value).replace(/'/g, '%27');
                }
                var btnEdit =
                    '<button class="btn btn-xs btn-default text-primary mx-1 shadow" title="Edit" onclick="editarUsuario(this)" data-area=\'' +
                    JSON.stringify({
                        id: row.id,
                        id_area: row.id_area,
                        'Nombre': customEncodeURIComponent(row['Nombre']),
                        "Área": row['área'],
                        Correo: row.Correo,
                        "creado en": row['creado en'],
                        "actualizado en": row['actualizado en'],
                    }) +
                    '\'><i class="fa fa-lg fa-fw fa-pen"></i></button> ';
                var btnDelete =
                    '<button class="btn btn-xs btn-default text-danger mx-1 shadow" title="Delete" onclick="eliminarUsuario(' +
                    row.id +
                    ')"><i class="fa fa-lg fa-fw fa-trash"></i></button> ';

                return '<nobr>' + btnEdit + btnDelete + '</nobr>';
            }

            refreshAreaDataTable();

            setInterval(refreshAreaDataTable, 10000);
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
            initializeSelect2('#r_area_id', areasDataRoute, 'q');
            initializeSelect2('#e_area_id', areasDataRoute, 'q');
        });
    </script>
@endsection
