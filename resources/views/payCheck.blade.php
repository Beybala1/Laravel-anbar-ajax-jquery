@extends('layouts.app')

@section('content')
@section('title')
<title>Ödəniş tarixçəsi</title>
@endsection
<div class="container mt-4">
    <div class="card">
        <div class="card-header text-center font-weight-bold">
            <h2>Ödəniş tarixçəsi</h2>
        </div>
        <div class="card-body table-responsive text-nowrap">
            <table class="table" id="datatable-ajax-crud">
                <thead>
                    <tr>
                        <th>№</th>
                        <th>Müştəri</th>
                        <th>Brend</th>
                        <th>Məhsul</th>
                        <th>Aylıq ödəniş</th>
                        <th>Tarix</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>

<script>
    $(document).ready(function () {

    $('.table').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ url('pay_check/'.$credit_id->id) }}",
            columns: [{
                    data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false
                },
                {
                    data: 'client',
                },
                {
                    data: 'brend',
                },
                {
                    data: 'product'
                },
                {
                    data: 'client_payed'
                },
                {
                    data: 'created_at',
                },
            ],
            order: [
                [0, 'desc']
            ],
            dom: 'Bfrltip',
            responsive: true,
            lengthChange: true,
            buttons: ['copy', 'excel', 'pdf', 'print', 'colvis']
        });
    });
</script>
@endsection
