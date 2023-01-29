@extends('layouts.app')
@section('content')

@section('title')
<title>Kredit</title>
@endsection

<div class="container mt-4">
    <div class="col-md-12 mt-1 mb-2"><button type="button" id="addNewBook" class="btn btn-success">Əlavə et</button></div>
    <div class="card">
        <div class="card-header text-center font-weight-bold">
            <h2>Kredit cədvəli</h2>
            @if (session()->has('message'))
            <div class="alert alert-success">
                {{ session('message') }}
            </div>
        @endif
        </div>
           
        <div class="card-body table-responsive text-nowrap">
            <table class="table" id="datatable-ajax-crud">
                <thead>
                    <tr>
                        <th>№</th>
                        <th>Kredit kodu</th>
                        <th>Müştəri</th>
                        <th>Brend</th>
                        <th>Məhsul</th>
                        <th>Müddət</th>
                        <th>Faiz</th>
                        <th>Miqdar</th>
                        <th>İlkin ödəniş</th>
                        <th>Depozit</th>
                        <th>Qiymət</th>
                        <th>Toplam borc</th>
                        <th>Qalıq borc</th>
                        <th>Aylıq</th>
                        <th>Ödəniş</th>
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
                                <select name="credit_client_id" id="credit_client_id" class="form-select">
                                    <option value="">Müştəri seçin</option>
                                    @foreach ($client_list as $client) 
                                        <option value="{{$client->id}}">{{$client->name}} {{$client->lastname}}</option> 
                                    @endforeach
                                </select>
                            </div>
                            <span class="text-danger error-text credit_client_id_error p-3 error_message"></span>
                        </div>

                        <div class="form-group">
                            <label for="name" class="col-sm-2 control-label">Məhsul</label>
                            <div class="col-sm-12">
                                <select name="credit_product_id" id="credit_product_id" class="form-select">
                                    <option value="">Məhsul seçin</option>
                                    @foreach ($product_list as $product) 
                                        <option value="{{$product->id}}">{{$product->product}} ({{$product->brend}}) - {{$product->count}}(ədəd) - {{$product->sell}}(AZN)</option> 
                                    @endforeach
                                </select>
                            </div>
                            <span class="text-danger error-text credit_product_id_error p-3 error_message"></span>
                        </div>

                        <div class="form-group">
                            <label for="name" class="col-sm-2 control-label">Müddət</label>
                            <div class="col-sm-12">
                                <select name="time" id="time" class="form-select">
                                    <option value="">Müddət seçin</option>
                                    <option value="6">6 ay</option>
                                    <option value="9">9 ay</option>
                                    <option value="12">12 ay</option>
                                </select>
                            </div>
                            <span class="text-danger error-text time_error p-3 error_message"></span>
                        </div>

                        <div class="form-group">
                            <label for="name" class="col-sm-2 control-label">Faiz</label>
                            <div class="col-sm-12">
                                <select name="percent" id="percent" class="form-select">
                                    <option value="">Faiz seçin</option>
                                    <option value="3">3%</option>
                                    <option value="6">6%</option>
                                    <option value="9">9%</option>
                                </select>
                            </div>
                            <span class="text-danger error-text percent_error p-3 error_message"></span>
                        </div>

                        <div class="form-group">
                            <label for="name" class="col-sm-2 control-label">Miqdar</label>
                            <div class="col-sm-12">
                                <input type="number" class="form-control" id="credit_count" name="credit_count"
                                    placeholder="Miqdar" maxlength="50">
                            </div>
                            <span class="text-danger error-text credit_count_error p-3 error_message"></span>
                        </div>

                        <div class="form-group">
                            <label for="name" class="col-sm-2 control-label">İlkin ödəniş</label>
                            <div class="col-sm-12">
                                <input type="number" class="form-control" id="first_payment" name="first_payment"
                                    placeholder="İlkin ödəniş" maxlength="50">
                            </div>
                            <span class="text-danger error-text first_payment_error p-3 error_message"></span>
                        </div>

                        <div class="col-sm-offset-2 col-sm-10">
                            <button type="submit" class="btn btn-primary" id="btn-save" value="addNewBook">Yadda saxla</button>
                            <button id="close" type="button" class="btn btn-danger" data-dismiss="modal">Ləğv et</button>
                        </div>
                    </form>
                </div>
                <div class="modal-footer"></div>
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
            ajax: "/credit",
            columns: [{
                    data: 'DT_RowIndex', orderable: false, searchable: false
                },
                {
                    data: 'credit_code',
                },
                {
                    data: 'client',
                },
                {   
                    data: 'brend',
                },
                {
                    data: 'product',
                },
                {
                    data: 'time',
                },
                {
                    data: 'percent',
                },
                {
                    data: 'credit_count',
                },
                {
                    data: 'first_payment',
                },
                {
                    data: 'depozit',
                },
                {
                    data: 'sell',
                },
                {
                    data: 'total_debt',
                },
                {
                    data: 'last_debt',
                },
                {
                    data: 'monthly',
                },
                {
                    data: 'month_payed',
                },
                {
                    data: 'datetime',
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
            $('#ajaxBookModel').html("Kredit daxil edin");
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
                url: "/credit/"+id+"/edit",
                data: {
                    id: id
                },
                dataType: 'json',
                success: function (res) {
                    $('#ajaxBookModel').html("Redaktə et");
                    $('#ajax-book-model').modal('show');
                    $('.error_message').html("");
                    $('#id').val(res.id);
                    $('#credit_client_id').val(res.credit_client_id);
                    $('#credit_product_id').val(res.credit_product_id);
                    $('#time').val(res.time);
                    $('#percent').val(res.percent);
                    $('#credit_count').val(res.credit_count);
                    $('#first_payment').val(res.first_payment);
                }
            });
        });

        $('body').on('click', '.cancel', function () {
            var id = $(this).data('id');
            var url = "/cancel";
            Swal.fire({
                title: "Krediti ləğv etmək istəyirsinizmi?",
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
                url: "/credit",
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

