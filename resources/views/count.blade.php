@php
    use App\Models\Brand; 
    use App\Models\Client; 
    use App\Models\Product; 
    use App\Models\Order; 
@endphp
<div class="row">
    
    <div class="col-lg-4 col-md-4 order-1">
        <div class="row">
            <div class="col-lg-6 col-md-12 col-6 mb-4">
                <div class="card">
                    <div class="card-body">
                        <span class="fw-semibold d-block mb-1">Müştəri</span>
                        <small style="font-size: 1.2rem; div"
                            class="text-success">{{Client::where('user_id','=',auth()->id())->count()}}</small>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 col-md-12 col-6 mb-4">
                <div class="card">
                    <div class="card-body">
                        <span class="fw-semibold d-block mb-1">Brend</span>
                        <small style="font-size: 1.2rem;"
                            class="text-success">{{Brand::where('user_id','=',auth()->id())->count()}}</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12 col-md-8 col-lg-4 order-3 order-md-2">
        <div class="row">
            <div class="col-6 mb-4">
                <div class="card">
                    <div class="card-body">
                        <span class="fw-semibold d-block mb-1">Məhsul</span>
                        <small style="font-size: 1.2rem;"
                            class="text-success">{{Product::where('user_id','=',auth()->id())->count()}}</small>
                    </div>
                </div>
            </div>
            <div class="col-6 mb-4">
                <div class="card">
                    <div class="card-body">
                        <span class="fw-semibold d-block mb-1">Sifariş</span>
                        <small style="font-size: 1.2rem;"
                            class="text-success">{{Order::where('user_id','=',auth()->id())->count()}}</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    

    
