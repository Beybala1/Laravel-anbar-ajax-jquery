@extends('layouts.app')

@section('content')
<title>Məhsul</title>

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

                    <form method="POST" action="/product/{{$form_data->id}}" enctype="multipart/form-data">
                        @csrf
                        @method("PUT")
                        <div class="mb-3">
                            <label class="form-label" for="basic-default-fullname">Məhsul</label>
                            <input type="text" name="product" value="{{$form_data->product}}" class="form-control @error('product') border-danger @enderror"
                                id="basic-default-fullname" placeholder="Məhsul" required="" />
                            @error('product')
                            <p class="text-danger">{{$message}}</p>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="defaultSelect" class="form-label">Brend</label>
                            <select name="brand_id" id="defaultSelect" class="form-select @error('brand_id') border-danger @enderror" required="">
                                <option value="">Brend seçin</option>
                                    @foreach ($brand_list as $brand) 
                                        @if ($brand->id == $form_data->brand_id)
                                            <option selected value="{{$brand->id}}">{{$brand->brend}}</option> 
                                            @else 
                                            <option value="{{$brand->id}}">{{$brand->brend}}</option>  
                                        @endif
                                    @endforeach
                            </select>
                            @error('brand_id')
                            <p class="text-danger">{{$message}}</p>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label" for="basic-default-fullname">Alış</label>
                            <input type="number" name="buy" value="{{$form_data->buy}}" class="form-control @error('buy') border-danger @enderror"
                                id="basic-default-fullname" placeholder="Alış" required="" />
                            @error('buy')
                            <p class="text-danger">{{$message}}</p>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label" for="basic-default-fullname">Satış</label>
                            <input type="number" name="sell" value="{{$form_data->sell}}" class="form-control @error('sell') border-danger @enderror"
                                id="basic-default-fullname" placeholder="Satış" required="" />
                            @error('sell')
                            <p class="text-danger">{{$message}}</p>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="number-label" for="basic-default-fullname">Miqdar</label>
                            <input type="number" name="count" value="{{$form_data->count}}" class="form-control @error('count') border-danger @enderror"
                                id="basic-default-fullname" placeholder="Miqdar" required="" />
                            @error('count')
                            <p class="text-danger">{{$message}}</p>
                            @enderror
                        </div>

                        <div class="input-group">
                            <input type="file" name="logo" class="form-control @error('logo') border-danger @enderror"
                                id="inputGroupFile02"  />
                            <label class="input-group-text" for="inputGroupFile02">Yüklə</label>
                        </div>

                        @error('logo')
                        <p class="text-danger">{{$message}}</p>
                        @enderror

                        <img class="mt-3" style="width:150px; height:120px;" src="{{url('storage/'.$form_data->logo)}}">

                        <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded mt-3">Yenilə</button>
                        <a href="/product"><button type="button" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded mt-3">Ləğv et</button></a>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <h5 class="card-header">Məhsul cədvəli</h5>
        <div class="table-responsive text-nowrap">
            <table class="table">
                <thead>
                    <tr class="text-nowrap">
                        <th>#</th>
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
                <tbody>
                    @foreach ($data_list as $i=>$data)
                    <tr>
                        <th scope="row">{{$i+=1}}</th>
                        <td><img style="width:80px; height:80px;" src="{{url('storage/'.$data->logo)}}"></td>
                        <td>{{$data->product}}</td>
                        <td>{{$data->brand->brend}}</td>
                        <td>{{$data->buy}}</td>
                        <td>{{$data->sell}}</td>
                        <td>{{$data->count}}</td>
                        <td>{{$data->created_at}}</td>
                        <td>
                            <div class="btn-group">
                                <a href="/product/{{$data->id}}/edit"><button class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded mr-2"><i class="bi bi-pencil-square"></i></button></a>
                                <form method="POST" action="/product/{{$data->id}}" enctype="multipart/form-data">
                                    @csrf
                                    @method('DELETE')
                                <button type="submit" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded"><i class="bi bi-x-lg"></i></button> 
                                </form>
                            </div>
                        </td> 
                    </tr> 
                    @endforeach
                    
                </tbody>
            </table>
        </div>
    </div>
    {{$data_list->links()}}
@endsection

