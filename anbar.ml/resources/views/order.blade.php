@extends('layouts.app')
@section('content')

@section('title')
<title>Sifariş</title>
@endsection

<div class="container mt-4">
    <div class="col-md-12 mt-1 mb-2"><button type="button" id="addNewBook" class="btn btn-success">Əlavə et</button></div>
    <div class="card">
        <div class="card-header text-center font-weight-bold">
            <h2>Sifariş cədvəli</h2>
        </div>
        <div class="card-body table-responsive text-nowrap">
            <table class="table" id="datatable-ajax-crud">
                <thead>
                    <tr>
                        <th>№</th>
                        <th>Müştəri</th>
                        <th>Məhsul</th>
                        <th>Brend</th>
                        <th>Miqdar</th>
                        <th>Sifariş Miqdar</th>
                        <th>Tarix</th>
                        <th>Əməliyyatlar</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
    <!-- boostrap add and edit book model -->
    <div class="modal fade" id="ajax-book-model" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="ajaxBookModel"></h4>
                </div>
                <div class="modal-body">
                    <form action="javascript:void(0)" id="addEditBookForm" name="addEditBookForm"
                        class="form-horizontal" method="POST" enctype="multipart/form-data">
                        <input type="hidden" name="id" id="id">
                        <div class="form-group">
                            <label for="name" class="col-sm-2 control-label">Müştəri</label>
                            <div class="col-sm-12">
                                <select name="client_id" id="client_id" class="form-select">
                                    <option value="">Müştəri seçin</option>
                                    @foreach ($client_list as $client) 
                                        <option value="{{$client->id}}">{{$client->name}} {{$client->lastname}}</option> 
                                    @endforeach
                                </select>
                            </div>
                            <span class="text-danger error-text client_id_error p-3 error_message"></span>
                        </div>

                        <div class="form-group">
                            <label for="name" class="col-sm-2 control-label">Məhsul</label>
                            <div class="col-sm-12">
                                <select name="product_id" id="product_id" class="form-select">
                                    <option value="">Məhsul seçin</option>
                                    @foreach ($product_list as $product) 
                                        <option value="{{$product->id}}">{{$product->product}} ({{$product->brend}}) - {{$product->count}}</option> 
                                    @endforeach
                                </select>
                            </div>
                            <span class="text-danger error-text product_id_error p-3 error_message"></span>
                        </div>

                        <div class="form-group">
                            <label for="name" class="col-sm-2 control-label">Miqdar</label>
                            <div class="col-sm-12">
                                <input type="number" class="form-control" id="order_count" name="order_count"
                                    placeholder="Miqdar" maxlength="50">
                            </div>
                            <span class="text-danger error-text order_count_error p-3 error_message"></span>
                        </div>

                        <div class="col-sm-offset-2 col-sm-10">
                            <button type="submit" class="btn btn-primary" id="btn-save" value="addNewBook">Yadda saxla</button>
                            <button id="close" type="button" class="btn btn-danger" data-dismiss="modal">Ləğv et</button>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">

                </div>
            </div>
        </div>
    </div>
</div> 
<!-- end bootstrap model -->
@section('scripts')
    
<script>

    $(document).ready(function () {

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $('#logo').change(function () {
            let reader = new FileReader();
            reader.onload = (e) => {
                $('#preview-image').attr('src', e.target.result);
            }
            reader.readAsDataURL(this.files[0]);
        });

        $('#datatable-ajax-crud').DataTable({
            processing: true,
            serverSide: true,
            ajax: "/order",
            columns: [{
                    data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false
                },
                {
                    data: 'client',
                },
                {
                    data: 'product',
                },
                {
                    data: 'brend',
                },
                {
                    data: 'count',
                },
                {
                    data: 'order_count',
                },
                {
                    data: 'created_at',
                },
                {
                    data: 'action',
                    name: 'action',
                    orderable: false
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

        $('#addNewBook').click(function () {
            $('#addEditBookForm').trigger("reset");
            $('#ajaxBookModel').html("Sifariş daxil edin");
            $('#ajax-book-model').modal('show');
            $('.error_message').html("");
            $('#id').val('');
        });

        $('#close').on('click',function(){
            $('#ajax-book-model').modal('hide');
        });

        $('body').on('click', '.edit', function () {
            var id = $(this).data('id');
            $.ajax({
                type: "GET",
                url: "/order/"+id+"/edit",
                dataType: 'JSON',
                data: {
                    id: id,
                },
                success: function (res) {
                    $('#ajaxBookModel').html("Redaktə et");
                    $('#ajax-book-model').modal('show');
                    $('.error_message').html("");
                    $('#id').val(res.id);
                    $('#client_id').val(res.client_id);
                    $('#product_id').val(res.product_id);
                    $('#order_count').val(res.order_count);
                }
            });
        });

        $(document).on('click', '.delete', function () {
            var id = $(this).data('id');
            Swal.fire({
                title: "Silmək istədiyinizə əminsinizmi?",
                text: "Məlumat silindiyi təqdirdə geri qaytramaq mümkün deyildir",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                cancelButtonText: 'Ləğv et',
                confirmButtonText: 'Sil'
            }).then(function (e) {
                if (e.value === true) {
                    var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
                    $.ajax({
                        type: 'DELETE',
                        url: "/order/"+id,
                        data: {_token: CSRF_TOKEN},
                        dataType: 'JSON',
                        success: function (results) {
                            if (results.code === 1) {
                                $('#datatable-ajax-crud').DataTable().ajax.reload(null, false);
                                Swal.fire({
                                    title: results.msg,
                                    icon: 'success',
                                    confirmButtonText: 'Geri dön',
                                    showCloseButton: true
                                })
                            }
                        }
                    });
                } else {
                    e.dismiss;
                }
            },  
            function (dismiss) {
                return false;
            })
        });

        $('body').on('click', '.confirm', function () {
            var id = $(this).data('id');
            var url = "{{ url('order_confirm') }}";
            Swal.fire({
                title: "Sifarişi təsdiq etmək istəyirsinizmi?",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                cancelButtonText: 'Ləğv et',
                confirmButtonText: 'Təsdiq et'
            })
            .then(function (result) {
                if (result.value) {
                    $.post(url, {
                        id: id
                    }, function (data) {
                        if (data.code == 1) {
                            $('#datatable-ajax-crud').DataTable().ajax.reload(null, false);
                            Swal.fire({
                                title: data.msg,
                                icon: 'success',
                                confirmButtonText: 'Geri dön',
                                showCloseButton: true
                            })
                        }
                        else{
                            $('#datatable-ajax-crud').DataTable().ajax.reload(null, false);
                            Swal.fire({
                            icon: 'error',
                            title: 'Xəta',
                            text: 'Anbarda kifayət qədər məhsul yoxdur',
                            })
                        }
                    }, 'json');
                }
            });
        });

        $('body').on('click', '.cancel', function () {
            var id = $(this).data('id');
            var url = "{{ url('order_cancel') }}";
            Swal.fire({
                title: "Sifarişi ləğv etmək istəyirsinizmi?",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                cancelButtonText: 'Geri dön',
                confirmButtonText: 'Ləğv et'
            })
            .then(function (result) {
                if (result.value) {
                    $.post(url, {
                        id: id
                    }, function (data) {
                        if (data.code == 1) {
                            $('#datatable-ajax-crud').DataTable().ajax.reload(null, false);
                            Swal.fire({
                                title: data.msg,
                                icon: 'success',
                                confirmButtonText: 'Geri dön',
                                showCloseButton: true
                            })
                        }
                    }, 'json');
                }
            });
        });

        $('#addEditBookForm').submit(function (e) {
            e.preventDefault();
            var formData = new FormData(this);
            $.ajax({
                type: 'POST',
                url: "/order",
                method: $(this).attr('method'),
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                beforeSend: function () {
                    $(document).find('span.error-text').text('');
                },
                success: (data) => {
                    if (data.status == 0) {
                        $.each(data.error, function (prefix, val) {
                            $('span.' + prefix + '_error').text(val[0]);
                        });
                    } else {
                        $("#ajax-book-model").modal('hide');
                        var oTable = $('#datatable-ajax-crud').dataTable();
                        oTable.fnDraw(false);
                        $("#btn-save").html('Yadda saxla');
                        $("#btn-save").attr("disabled", false);
                        $('#addEditBookForm')[0].reset();
                        Swal.fire({
                            title: "Əməliyyat uğurla həyata keçirildi",
                            icon: 'success',
                            confirmButtonText: 'Geri dön',
                            showCloseButton: true
                        })
                    }
                },
            });
        });
    });

</script>
@endsection

@endsection
