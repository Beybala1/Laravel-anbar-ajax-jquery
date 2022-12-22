@extends('layouts.app')
@section('content')

@section('title')
<title>Admin</title>
@endsection
<div class="container mt-4">
    <div class="card">
        <div class="card-header text-center font-weight-bold">
            <h2>Admin cədvəli</h2>
        </div>
        <div class="card-body table-responsive text-nowrap">
            <table class="table" id="datatable-ajax-crud">
                <thead>
                    <tr>
                        <th>№</th>
                        <th>İstifadəçi adı</th>
                        <th>Email</th>
                        <th>Status</th>
                        <th>Tarix</th>
                        <th>Əməliyyatlar</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div> 
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
            ajax: "{{ url('/admin') }}",
            columns: [{
                    data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false
                },
                {
                    data: 'name',
                },
                {
                    data: 'email',
                },
                {
                    data: 'status',
                    /*render: function (data, type, full, meta) {
                        return '<span class="text-success">'+ data +'</span>';
                    },*/
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
            $('#ajaxBookModel').html("İstifadəçi daxil edin");
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
                url: "{{ url('brand_update') }}",
                data: {
                    id: id
                },
                dataType: 'json',
                success: function (res) {
                    $('#ajaxBookModel').html("Redaktə et");
                    $('#ajax-book-model').modal('show');
                    $('.error_message').html("");
                    $('#id').val(res.id);
                    $('#brend').val(res.brend);
                    document.getElementById('preview-image').src = res.logo
                }
            });
        });

        $(document).on('click', '.delete', function () {
            var id = $(this).data('id');
            var url = "{{ url('/user_delete') }}";
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
                        id: id,
                    },function (data) {
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

        $('body').on('click', '.block', function () {
            var id = $(this).data('id');
            var url = "{{ url('user_block') }}";
            Swal.fire({
                title: "İstifadəçini blok etmək istəyirsinizmi?",
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
                    }, 'json');
                }
            });
        });

        $('body').on('click', '.unblock', function () {
            var id = $(this).data('id');
            var url = "{{ url('user_unblock') }}";
            Swal.fire({
                title: "İstifadəçini blokdan çıxarmaq istəyirsinizmi?",
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
                    }, 'json');
                }
            });
        });

        $('#addEditBookForm').submit(function (e) {
            e.preventDefault();
            var formData = new FormData(this);
            $.ajax({
                type: 'POST',
                url: "{{ url('brand_insert')}}",
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
