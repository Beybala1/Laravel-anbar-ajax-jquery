@extends('layouts.app')
@section('content')

@section('title')
    <title>Xerc</title>
@endsection

<div class="container mt-4">
    <div class="col-md-12 mt-1 mb-2"><button type="button" id="addNewBook" class="btn btn-success">Əlavə et</button></div>
    <div class="card">
        <div class="card-header text-center font-weight-bold">
            <h2>Xerc cədvəli</h2>
        </div>
        <div class="card-body table-responsive text-nowrap">
            <table class="table" id="datatable-ajax-crud">
                <thead>
                    <tr>
                        <th>№</th>
                        <th>Xerc</th>
                        <th>Miqdar</th>
                        <th>Tarix</th>
                        <th>Əməliyyatlar</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
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
                                <input type="text" class="form-control" id="xerc_product" name="xerc_product"
                                    placeholder="Məhsul" maxlength="50">
                            </div>
                            <span class="text-danger error-text xerc_product_error p-3 error_message"></span>
                        </div>

                        <div class="form-group">
                            <label for="name" class="col-sm-2 control-label">Miqdar</label>
                            <div class="col-sm-12">
                                <input type="number" class="form-control" id="count" name="count"
                                    placeholder="Miqdar" maxlength="50">
                            </div>
                            <span class="text-danger error-text count_error p-3 error_message"></span>
                        </div>

                        <div class="col-sm-offset-2 col-sm-10">
                            <button type="submit" class="btn btn-primary" id="btn-save" value="addNewBook">Yadda saxla
                            </button>
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
    
<script type="text/javascript">
     
 $(document).ready( function () {

    $.ajaxSetup({
        headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $('#datatable-ajax-crud').DataTable({
           processing: true,
           serverSide: true,
           ajax: "/xerc",
           columns: [
                    {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},
                    { data: 'xerc_product' },
                    { data: 'count'},
                    { data: 'created_at'},
                    {data: 'action', name: 'action', orderable: false},
                 ],
          order: [[0, 'desc']],
            dom: 'Bfrltip',
            responsive: true,
            lengthChange: true,
            buttons: ['copy', 'excel', 'pdf', 'print', 'colvis']
    });


    $('#addNewBook').click(function () {
       $('#addEditBookForm').trigger("reset");
       $('#ajaxBookModel').html("Xərc Daxil et");
       $('#ajax-book-model').modal('show');
       $('.error_message').html("");
       $('#id').val('');
    });

    $('#close').on('click',function(){
        $('#ajax-book-model').modal('hide');
    }),

 
    $('body').on('click', '.edit', function () {
        var id = $(this).data('id');
        $.ajax({
            type: "GET",
            url: "/xerc/"+id+"/edit",
            data: { id: id },
            dataType: 'json',
            success: function(res){
              $('#ajaxBookModel').html("Redaktə et");
              $('#ajax-book-model').modal('show');
              $('.error_message').html("");
              $('#id').val(res.id);
              $('#xerc_product').val(res.xerc_product);
              $('#count').val(res.count);
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
        })
        .then(function (e) {
                if (e.value === true) {
                    var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
                    $.ajax({
                        type: 'DELETE',
                        url: "/xerc/" + id,
                        data: {
                            _token: CSRF_TOKEN
                        },
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

    $('#addEditBookForm').submit(function (e) {

        e.preventDefault();

        var formData = new FormData(this);

        $.ajax({
            type: 'POST',
            url: "/xerc",
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
            error: function (data) {
                console.log(data);
            }
        });
    });
});
</script>

@endsection

@endsection

