@extends('layouts.app')
@section('content')

@section('title')
<title>İşçilərin dokumentləri</title>
@endsection

<div class="container mt-4">
    <div class="col-md-12 mt-1 mb-2"><button type="button" id="addNewBook" class="btn btn-success">Əlavə et</button></div>
    <div class="card">
        <div class="card-header text-center font-weight-bold">
            <h2>İşçilərin dokument cədvəli</h2>
        </div>
        <div class="card-body table-responsive text-nowrap">
            <table class="table" id="datatable-ajax-crud">
                <thead>
                    <tr>
                        <th>№</th>
                        <th>Başlıq</th>
                        <th>Foto</th>
                        <th>Haqqında</th>
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
                        @if ($employees_id)
                            <input type="hidden" name="employee_id" value="{{$employees_id->id}}">
                        @endif
                        <div class="form-group">
                            <label for="name" class="col-sm-2 control-label">Başlıq</label>
                            <div class="col-sm-12">
                                <input type="text" class="form-control" id="title" name="title" placeholder="Başlıq"
                                    maxlength="50">
                            </div>
                            <span class="text-danger error-text title_error p-3 error_message"></span>
                        </div>
                      
                        <div class="form-group">
                            <label class="col-sm-2 control-label">Foto</label>
                            <div class="col-sm-6 pull-left">
                                <input type="file" class="form-control" id="scan" name="scan">
                            </div>
                            <span class="text-danger error-text scan_error p-3 error_message"></span>
                            <div class="col-sm-6 pull-right">
                                <img id="preview-image"
                                    src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcSM4sEG5g9GFcy4SUxbzWNzUTf1jMISTDZrTw&usqp=CAU"
                                    alt="preview image" style="max-height: 250px;">
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="name" class="col-sm-2 control-label">Haqqında</label>
                            <div class="col-sm-12">
                                <textarea name="about" class="form-control" id="about" cols="30" rows="10"></textarea>
                            </div>
                            <span class="text-danger error-text about_error p-3 error_message"></span>
                        </div>

                        <div class="col-sm-offset-2 col-sm-10">
                            <button type="submit" class="btn btn-primary" id="btn-save" value="addNewBook">Yadda
                                saxla</button>
                            <button id="close" type="button" class="btn btn-danger" data-dismiss="modal">Ləğv
                                et</button>
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
    
<script>

    $(document).ready(function () {

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $('#scan').change(function () {
            let reader = new FileReader();
            reader.onload = (e) => {
                $('#preview-image').attr('src', e.target.result);
            }
            reader.readAsDataURL(this.files[0]);
        });

        $('#datatable-ajax-crud').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ url('employee_document/'.$employees_id->id) }}",
            columns: [{
                    data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false
                },
                {
                    data: 'title',
                },
                {
                    data: 'image',
                    orderable: false,
                    render: function (data, type, full, meta) {
                        return '<img style="width:50px; height:50px; " src="' + data + '">'
                    },
                },
                {
                    data: 'about',
                },
                {
                    data: 'date'
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
            $('#ajaxBookModel').html("İşçi daxil et");
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
                url: "{{ url('employee_document_update') }}",
                data: {
                    id: id
                },
                dataType: 'json',
                success: function (res) {
                    $('#ajaxBookModel').html("Redaktə et");
                    $('#ajax-book-model').modal('show');
                    $('.error_message').html("");
                    $('#id').val(res.id);
                    $('#title').val(res.title);
                    $('#about').val(res.about);
                    document.getElementById('preview-image').src = window.location.origin+'/'+res.scan
                }
            });
        });

        $(document).on('click','.delete', function(){
            var id = $(this).data('id');
            var url = "{{ url('employee_document_delete') }}";                
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

        $('#addEditBookForm').submit(function (e) {
            e.preventDefault();
            var formData = new FormData(this);
            $.ajax({
                type: 'POST',
                url: "{{ url('employee_document_insert')}}",
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
