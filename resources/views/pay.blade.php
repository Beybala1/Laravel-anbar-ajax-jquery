@extends('layouts.app')

@section('content')
@section('title')
<title>Ödəniş</title>
@endsection

    <div class="row">
        <div class="col-xl">
            <div class="card mb-4">
                <div class="card-body">
                    @if (session('message'))
                    <div class="alert alert-success d-flex align-items-center" role="alert">
                        <i class="bi bi-check-circle-fill bi flex-shrink-0 me-2"></i>
                        <div>
                            {{session('message')}}
                        </div>
                    </div>
                    @endif

                    @if (session('message_fail'))
                    <div class="alert alert-danger d-flex align-items-center" role="alert">
                        <i class="bi bi-exclamation-triangle-fill bi flex-shrink-0 me-2"></i>                        
                        <div>
                            {{session('message_fail')}}
                        </div>
                      </div>
                    @endif
                    <form method="POST" action="/pay_confirm/{{$credit->credit_id}}">
                        @csrf
                        <div class="mb-3">
                            <h5>Müştəri: <small class="text-muted">{{$credit->name}} {{$credit->lastname}}</small></h5>
                            <h5>Məhsul: <small class="text-muted">{{$credit->product}} ({{$credit->brend}})</small></h5>
                            <h5>Ödəniləcək məbləğ: <small class="text-muted">{{(mb_substr($monthly,0,5))}} (AZN)</small></h5>
                            <h5>Depozit: <small class="text-muted">{{(mb_substr($credit->depozit,0,5))}} (AZN)</small></h5>
                            <label class="form-label" for="basic-default-fullname">Məbləğ</label>
                            <input type="text" name="price" value="{{old('price')}}" class="form-control @error('price') border-danger @enderror"
                                id="basic-default-fullname" placeholder="Məbləğ daxil edin" required="" autocomplete="price"/>
                            @error('price')
                            <p class="text-danger">{{$message}}</p>
                            @enderror
                        </div>
                        <button type="submit" class="btn btn-success">Ödəniş et</button>
                        <button type="button" class="btn btn-danger" onclick="myFunction()">Geri</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        
        function myFunction() {
            location.replace("/credit")
        }

       
    </script>
@endsection

