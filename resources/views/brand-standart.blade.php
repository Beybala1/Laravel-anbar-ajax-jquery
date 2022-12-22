use App\Models\Product;
@extends('layouts.app')

@section('content')
<title>Brend</title>

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
   
                    <form method="POST" action="/brand" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label" for="basic-default-fullname">Brend</label>
                            <input type="text" name="brend" value="{{old('brend')}}" class="form-control @error('brend') border-danger @enderror"
                                id="basic-default-fullname" placeholder="Brend" required="" />
                            @error('brend')
                            <p class="text-danger">{{$message}}</p>
                            @enderror
                        </div>

                        <div class="input-group">
                            <input type="file" name="logo" class="form-control @error('logo') border-danger @enderror"
                                id="inputGroupFile02" required="" />
                            <label class="input-group-text" for="inputGroupFile02">Yüklə</label>
                        </div>

                        @error('logo')
                        <p class="text-danger">{{$message}}</p>
                        @enderror
                        <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded mt-3">Daxil et</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <h5 class="card-header">Brend cədvəli</h5>
        <div class="table-responsive text-nowrap">
            <table class="table">
                <thead>
                    <tr class="text-nowrap">
                        <th>#</th>
                        <th>Logo</th>
                        <th>Brend</th>
                        <th>Tarix</th>
                        <th>Əməliyyatlar</th>
                    </tr>
                </thead>
                <tbody>
                                        @foreach ($data_list as $i=>$data)
                    <tr>
                        <th scope="row">{{$i+=1}}</th>
                        <td><img style="width:80px; height:80px;" src="{{url('storage/'.$data->logo)}}"></td>
                        <td>{{$data->brend}}</td>
                        <td>{{$data->created_at}}</td>
                        <td>
                            @php($x = '')
                            @foreach ($data_product as $check)
                                @if($check->brand_id == $data->id)
                                    @php($x = 'disabled')
                                @endif
                            @endforeach

                            <div class="btn-group">
                                <a href="/brand/{{$data->id}}/edit" style="border-radius: 15px" class="btn btn-success mr-2"><i class="bi bi-pencil-square"></i></a>
                                @if ($x == 'disabled')
                                    <span class="d-inline-block" tabindex="0" data-toggle="tooltip" title="Bu brend aktifdir.">
                                        <button disabled style="border-radius: 15px; pointer-events: none;" class="btn btn-danger"><i class="bi bi-x-lg"></i></button>
                                    </span>                                    
                                    @else
                                    <a href="#" style="border-radius: 15px" class="btn btn-danger delete" data-id={{$data->id}}><i class="bi bi-x-lg"></i></a>
                                @endif
                            </div>
                        </td> 
                    </tr> 
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    {{$data_list->links()}}

    <script>
        $('.delete').click(function(){
            let id = $(this).attr('data-id');
            swal({
                    title: "Silmək istədiyinizə əminsinizmi?",
                    text: "Məlumat silindiyi təqdirdə geri qaytramaq mümkün deyildir",
                    icon: "warning",
                    buttons: ["Ləğv et", "Sil"],
                    dangerMode: true,
                })
            .then((willDelete) => {
                if (willDelete) {
                    window.location = "/brand/"+id+""
                    swal("Uğurla silindi", {
                        icon: "success",
                    });
                } 
            });
        });

    </script>
@endsection

