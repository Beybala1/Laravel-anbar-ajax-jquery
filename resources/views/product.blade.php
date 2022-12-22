@extends('layouts.app')
@section('content')

@section('title')
<title>Məhsul</title>
@endsection

<div class="container mt-4">
    <div class="col-md-12 mt-1 mb-2"><button type="button" id="addNewBook" class="btn btn-success">Əlavə et</button></div>
    <div class="card">
        <div class="card-header text-center font-weight-bold">
            <h2>Məhsul cədvəli</h2>
        </div>
        <div class="card-body table-responsive text-nowrap">
            <table class="table" id="datatable-ajax-crud">
                <thead>
                    <tr>
                        <th>№</th>
                        <th>Logo</th>
                        <th>Məhsul</th>
                        <th>Brend</th>
                        <th>Alış</th>
                        <th>Satış</th>
                        <th>Miqdar</th>
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
                            <label for="name" class="col-sm-2 control-label">Məhsul</label>
                            <div class="col-sm-12">
                                <input type="text" name="product" class="form-control" id="product" 
                                    placeholder="Məhsul" maxlength="50">
                            </div>
                            <span class="text-danger error-text product_error p-3 error_message"></span>
                        </div>

                        <div class="form-group">
                            <label for="name" class="col-sm-2 control-label">Brend</label>
                            <div class="col-sm-12">
                                <select name="brand_id" id="brand_id" class="form-select">
                                    <option value="">Brend seçin</option>
                                    @foreach ($brand_list as $brand) 
                                        <option value="{{$brand->id}}">{{$brand->brend}}</option> 
                                    @endforeach
                                </select>
                            </div>
                            <span class="text-danger error-text brand_id_error p-3 error_message"></span>
                        </div>

                        <div class="form-group">
                            <label for="name" class="col-sm-2 control-label">Alış</label>
                            <div class="col-sm-12">
                                <input type="number" class="form-control" id="buy" name="buy"
                                    placeholder="Alış" maxlength="50">
                            </div>
                            <span class="text-danger error-text buy_error p-3 error_message"></span>
                        </div>

                        <div class="form-group">
                            <label for="name" class="col-sm-2 control-label">Satış</label>
                            <div class="col-sm-12">
                                <input type="number" class="form-control" id="sell" name="sell"
                                    placeholder="Satış" maxlength="50">
                            </div>
                            <span class="text-danger error-text sell_error p-3 error_message"></span>
                        </div>

                        <div class="form-group">
                            <label for="name" class="col-sm-2 control-label">Miqdar</label>
                            <div class="col-sm-12">
                                <input type="number" class="form-control" id="count" name="count"
                                    placeholder="Miqdar" maxlength="50">
                            </div>
                            <span class="text-danger error-text count_error p-3 error_message"></span>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-2 control-label">Logo</label>
                            <div class="col-sm-6 pull-left">
                                <input type="file" class="form-control" id="logo" name="logo">
                            </div>
                            <span class="text-danger error-text logo_error p-3 error_message"></span>
                            <div class="col-sm-6 pull-right">
                                <img id="preview-image"
                                    src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcSM4sEG5g9GFcy4SUxbzWNzUTf1jMISTDZrTw&usqp=CAU"
                                    alt="preview image" style="max-height: 250px;">
                            </div>
                        </div>

                        <div class="col-sm-offset-2 col-sm-10">
                            <button type="submit" class="btn btn-primary mt-2" id="btn-save" value="addNewBook">Yadda saxla</button>
                            <button id="close" type="button" class="btn btn-danger mt-2" data-dismiss="modal">Ləğv et</button>
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
            ajax: "{{ url('product') }}",
            columns: [{
                    data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false
                },
                {
                    data: 'products_logo',
                    render: function (data, type, full, meta) {
                        return '<img style="width:50px; height:50px; " src="' + data + '">'
                    },
                    orderable: false
                },
                {
                    data: 'product',
                },
                {
                    data: 'brend',
                },
                {
                    data: 'buy',
                },
                {
                    data: 'sell',
                },
                {
                    data: 'count',
                },
                {
                    data: 'datetime',
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
            $('#ajaxBookModel').html("Məhsul daxil edin");
            $('#ajax-book-model').modal('show');
            $('.error_message').html("");
            $('#id').val('');
            $('#preview-image').attr('src',
                'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcSM4sEG5g9GFcy4SUxbzWNzUTf1jMISTDZrTw&usqp=CAU'
            );
        });

        $('#close').on('click',function(){
            $('#ajax-book-model').modal('hide');
        });

        $('body').on('click', '.edit', function () {
            var id = $(this).data('id');
            $.ajax({
                type: "POST",
                url: "{{ url('product_update') }}",
                data: {
                    id: id
                },
                dataType: 'json',
                success: function (res) {
                    $('#ajaxBookModel').html("Redaktə et");
                    $('#ajax-book-model').modal('show');
                    $('.error_message').html("");
                    $('#id').val(res.id);
                    $('#product').val(res.product);
                    $('#brand_id').val(res.brand_id);
                    $('#buy').val(res.buy);
                    $('#sell').val(res.sell);
                    $('#count').val(res.count);
                    document.getElementById('preview-image').src = res.logo
                }
            });
        });

        $(document).on('click', '.delete', function () {
            var id = $(this).data('id');
            var url = "{{ url('product_delete') }}";
            Swal.fire({
                title: "Silmək istədiyinizə əminsinizmi?",
                text: "Məlumat silindiyi təqdirdə geri qaytramaq mümkün deyildir",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                cancelButtonText: 'Ləğv et',
                confirmButtonText: 'Sil'
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
                url: "{{ url('product_insert')}}",
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
                        Swal.fire("Əməliyyat uğurla həyata keçirildi", data.msg, "success");
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
