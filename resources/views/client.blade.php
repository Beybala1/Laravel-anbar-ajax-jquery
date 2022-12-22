@extends('layouts.app')
@section('content')

    @section('title')
        <title>Müştəri</title>
    @endsection

    <div class="container mt-4">
        <div class="col-md-12 mt-1 mb-2"><button type="button" id="addNewBook"
            class="btn btn-success">Əlavə
            et</button></div>
        <div class="card">
            <div class="card-header text-center font-weight-bold">
                <h2>Müştəri cədvəli</h2>
            </div>
            <div class="card-body table-responsive text-nowrap">
                <table class="table" id="datatable-ajax-crud">
                    <thead>
                        <tr>
                            <th>№</th>
                            <th>Ad</th>
                            <th>Soyad</th>
                            <th>Telefon</th>
                            <th>E-mail</th>
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
                                <label for="name" class="col-sm-2 control-label">Ad</label>
                                <div class="col-sm-12">
                                    <input type="text" class="form-control" id="name" name="name" placeholder="Ad"
                                        autocomplete="name" maxlength="50">
                                </div>
                                <span class="text-danger error-text name_error p-3 error_message"></span>
                            </div>

                            <div class="form-group">
                                <label for="name" class="col-sm-2 control-label">Soyad</label>
                                <div class="col-sm-12">
                                    <input type="text" class="form-control" id="lastname" name="lastname"
                                        placeholder="Soyad" maxlength="50">
                                </div>
                                <span class="text-danger error-text lastname_error p-3 error_message"></span>
                            </div>

                            <div class="form-group">
                                <label for="name" class="col-sm-2 control-label">Telefon</label>
                                <div class="col-sm-12">
                                    <input type="tel" class="form-control" id="phone" name="phone" placeholder="Telefon"
                                        maxlength="50">
                                </div>
                                <span class="text-danger error-text phone_error p-3 error_message"></span>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-2 control-label">Email</label>
                                <div class="col-sm-12">
                                    <input type="email" class="form-control" id="email" name="email" placeholder="Email">
                                </div>
                                <span class="text-danger error-text email_error p-3 error_message"></span>
                            </div>

                            <div class="col-sm-offset-2 col-sm-10">
                                <input type="hidden" name="button_action" id="button_action" value="insert" />
                                <button type="submit"
                                    class="btn btn-primary"
                                    id="btn-save" value="addNewBook">
                                    Yadda saxla
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

    @section('scripts')

    <script type="text/javascript">
        $(document).ready(function () {

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $('#datatable-ajax-crud').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ url('client') }}",
                columns: [{
                        data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false
                    },
                    {
                        data: 'name',
                    },
                    {
                        data: 'lastname',
                    },
                    {
                        data: 'phone',
                    },
                    {
                        data: 'email',
                    },
                    {
                        data: 'created_at',
                    },
                    {
                        data: 'action',
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
                $('#ajaxBookModel').html("Müştəri əlavə et");
                $('.error_message').html("");
                $('#ajax-book-model').modal('show');
                $('#id').val('');
            });

            $('#close').on('click',function(){
                $('#ajax-book-model').modal('hide');
            });

            $("#addEditBookForm").on('submit', function (e) {
                e.preventDefault();

                var formData = new FormData(this);

                $.ajax({
                    type: 'POST',
                    url: "{{ url('client_insert')}}",
                    method: $(this).attr('method'),
                    data: formData,
                    dataType: 'json',
                    contentType: false,
                    processData: false,
                    beforeSend: function () {
                        $(document).find('span.error-text').text('');
                    },
                    success: function (data) {
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
                    error: function (data) {
                        console.log(data);
                    }
                });
            });

            $('body').on('click', '.edit', function () {

                var id = $(this).data('id');

                // ajax
                $.ajax({
                    type: "POST",
                    url: "{{ url('client_update') }}",
                    data: {
                        id: id
                    },
                    dataType: 'json',
                    success: function (res) {
                        $('#ajaxBookModel').html("Redaktə et");
                        $('#ajax-book-model').modal('show');
                        $('#id').val(res.id);
                        $('#name').val(res.name);
                        $('#lastname').val(res.lastname);
                        $('#phone').val(res.phone);
                        $('#email').val(res.email);
                        $('.error_message').html("");
                    }
                });
            });

        $(document).on('click','.delete', function(){
            var id = $(this).data('id');
            var url = "{{ url('client_delete') }}";                
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
            .then(function(result){
                if(result.value){
                    $.post(url,{id:id}, function(data){
                        if(data.code == 1){
                            $('#datatable-ajax-crud').DataTable().ajax.reload(null, false);
                            Swal.fire({
                                title: data.msg,
                                icon: 'success',
                                confirmButtonText: 'Geri dön',
                                showCloseButton: true
                            })
                        }
                    },'json');
                }
            });
        });
    });

    </script>
    @endsection

@endsection
