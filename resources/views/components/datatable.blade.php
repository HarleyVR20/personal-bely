<div class=""> {{-- table-responsive --}}
    <table id="{{ $id }}" class="table" style="width:100%"> {{-- table-sm table-striped --}}
        <thead>
            <tr>
                @foreach ($columns as $column)
                    <th>{{ $column }}</th>
                @endforeach
            </tr>
        </thead>
    </table>
</div>
