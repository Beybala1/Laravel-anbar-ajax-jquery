<!DOCTYPE html>
<html lang="az" class="light-style layout-menu-fixed" dir="ltr" data-theme="theme-default" data-assets-path="/assets/"
    data-template="vertical-menu-template-free">

<head>
    <meta charset="utf-8" />
    <meta name="viewport"
        content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />
    @yield('title')
    <meta name="description" content="" />
    <link rel="icon" type="image/x-icon" href="{{url('public/assets/img/favicon/favicon.ico')}}" />
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.9.1/font/bootstrap-icons.css">
    <link
        href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap"
        rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    {{--Datatable--}}
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" type="text/css"
        href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.2.0/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.12.1/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" type="text/css"
        href="https://cdn.datatables.net/buttons/2.2.3/css/buttons.bootstrap5.min.css">
    <script type="text/javascript" language="javascript" src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <script type="text/javascript" language="javascript"
        src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
    <script type="text/javascript" language="javascript"
        src="https://cdn.datatables.net/1.12.1/js/dataTables.bootstrap5.min.js"></script>
    <script type="text/javascript" language="javascript"
        src="https://cdn.datatables.net/buttons/2.2.3/js/dataTables.buttons.min.js"></script>
    <script type="text/javascript" language="javascript"
        src="https://cdn.datatables.net/buttons/2.2.3/js/buttons.bootstrap5.min.js"></script>
    <script type="text/javascript" language="javascript"
        src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script type="text/javascript" language="javascript"
        src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
    <script type="text/javascript" language="javascript"
        src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
    <script type="text/javascript" language="javascript"
        src="https://cdn.datatables.net/buttons/2.2.3/js/buttons.html5.min.js"></script>
    <script type="text/javascript" language="javascript"
        src="https://cdn.datatables.net/buttons/2.2.3/js/buttons.print.min.js"></script>
    <script type="text/javascript" language="javascript"
        src="https://cdn.datatables.net/buttons/2.2.3/js/buttons.colVis.min.js"></script>
    {{--Assetler--}}
    <link rel="stylesheet" href="{{url('public/assets/vendor/fonts/boxicons.css')}}" />
    <link rel="stylesheet" href="{{url('public/assets/vendor/css/core.css')}}" class="template-customizer-core-css" />
    <link rel="stylesheet" href="{{url('public/assets/vendor/css/theme-default.css')}}" class="template-customizer-theme-css" />
    <link rel="stylesheet" href="{{url('public/assets/css/demo.css')}}" />
    <link rel="stylesheet" href="{{url('public/assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css')}}" />
    <link rel="stylesheet" href="{{url('public/assets/vendor/libs/apex-charts/apex-charts.css')}}" />
    <script src="{{url('public/assets/vendor/js/helpers.js')}}"></script>
    <script src="{{url('public/assets/js/config.js')}}"></script>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body>
    <div class="layout-wrapper layout-content-navbar">
        <div class="layout-container">
            <aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
                <div class="app-brand demo">
                    <a href="/" class="app-brand-link">
                        <span class="app-brand-text demo menu-text fw-bolder ms-2">Anbar</span>
                    </a>

                    <a href="javascript:void(0);"
                        class="layout-menu-toggle menu-link text-large ms-auto d-block d-xl-none">
                        <i class="bx bx-chevron-left bx-sm align-middle"></i>
                    </a>
                </div>
                <div class="menu-inner-shadow"></div>
                <ul class="menu-inner py-1">
                    <li class="menu-item">
                        <a href="/client" class="menu-link">
                            <i style="font-size: 1.7rem;" class="bi bi-people me-4"></i>
                            <div data-i18n="Analytics">Müştəri</div>
                        </a>
                    </li>
                    <li class="menu-item">
                        <a href="/" class="menu-link">
                            <i style="font-size: 1.7rem;" class="bi bi-hearts me-4"></i>
                            <div data-i18n="Analytics">Brend</div>
                        </a>
                    </li>
                    <li class="menu-item">
                        <a href="/product" class="menu-link">
                            <i style="font-size: 1.7rem;" class="bi bi-phone me-4"></i>
                            <div data-i18n="Analytics">Məhsul</div>
                        </a>
                    </li>
                    <li class="menu-item">
                        <a href="/order" class="menu-link">
                            <i style="font-size: 1.7rem;" class="bi bi-bag me-4"></i>
                            <div data-i18n="Analytics">Sifariş</div>
                        </a>
                    </li>
                    <li class="menu-item">
                        <a href="/xerc" class="menu-link">
                            <i style="font-size: 1.7rem;" class="bi bi-cash-stack me-4"></i>
                            <div data-i18n="Analytics">Xərc</div>
                        </a>
                    </li>
                    <li class="menu-item">
                        <a href="/employee" class="menu-link">
                            <i style="font-size: 1.7rem;" class="bi bi-person-lines-fill me-4"></i>
                            <div data-i18n="Analytics">İşçilər</div>
                        </a>
                    </li>
                    <li class="menu-item">
                        <a href="/credit" class="menu-link">
                            <i style="font-size: 1.7rem;" class="bi bi-credit-card-fill me-4"></i>
                            <div data-i18n="Analytics">Kredit</div>
                        </a>
                    </li>
                    @if (auth()->user()->email=='admin@gmail.com')
                        <li class="menu-item">
                            <a href="/admin" class="menu-link">
                                <i style="font-size: 1.7rem;" class="bi bi-person-circle me-4"></i>
                                <div data-i18n="Analytics">Admin</div>
                            </a>
                        </li> 
                    @endif
                </ul>
            </aside>
            <div class="layout-page">
                <nav class="layout-navbar container-xxl navbar navbar-expand-xl navbar-detached align-items-center bg-navbar-theme"
                    id="layout-navbar">
                    <div class="layout-menu-toggle navbar-nav align-items-xl-center me-3 me-xl-0 d-xl-none">
                        <a class="nav-item nav-link px-0 me-xl-4" href="javascript:void(0)">
                            <i class="bx bx-menu bx-sm"></i>
                        </a>
                    </div>
                    <div class="navbar-nav-right d-flex align-items-center" id="navbar-collapse">
                        <ul class="navbar-nav flex-row align-items-center ms-auto">
                            <li class="nav-item navbar-dropdown dropdown-user dropdown">
                                <a class="nav-link dropdown-toggle hide-arrow" href="javascript:void(0);"
                                    data-bs-toggle="dropdown">
                                    <div class="avatar avatar-online">
                                        <img src="{{url(auth()->user()->logo)}}" alt class="rounded-circle" />
                                    </div>
                                </a>
                                <ul class="dropdown-menu dropdown-menu-end">
                                    <li>
                                        <a class="dropdown-item" href="#">
                                            <div class="d-flex">
                                                <div class="flex-shrink-0 me-3">
                                                    <div class="avatar avatar-online">
                                                        <img src="{{url(auth()->user()->logo)}}" alt class="rounded-circle" />
                                                    </div>
                                                </div>
                                                <div class="flex-grow-1">
                                                    <span class="fw-semibold d-block">{{auth()->user()->name}}</span>
                                                    <small class="text-muted">Admin</small>
                                                </div>
                                            </div>
                                        </a>
                                    </li>
                                    <li>
                                        <div class="dropdown-divider"></div>
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="/profile">
                                            <i class="bx bx-user me-2"></i>
                                            <span class="align-middle">Profil</span>
                                        </a>
                                        <a class="dropdown-item" href="/logout">
                                            <i class="bx bx-power-off me-2"></i>
                                            <span class="align-middle">Çıxış</span>
                                        </a>
                                    </li>
                                </ul>
                            </li>
                        </ul>
                    </div>
                </nav>
                <div class="content-wrapper">
                    <div class="container-xxl flex-grow-1 container-p-y">
                        <div class="row">
                            <div class="col-lg-4 col-md-4 order-1">
                                <div class="row">
                                    <div class="col-lg-6 col-md-12 col-6 mb-4">
                                        <div class="card">
                                            <div class="card-body">
                                                <span class="fw-semibold d-block mb-1">Müştəri</span>
                                                <small id="count_client" style="font-size: 1.2rem; div" class="text-success">
                                                    {{App\Models\Client::where('user_id','=',auth()->id())->count()}}
                                                </small>
                                            </div>
                                        </div>
                                    </div>
                                   
                                    <div class="col-lg-6 col-md-12 col-6 mb-4">
                                        <div class="card">
                                            <div class="card-body">
                                                <span class="fw-semibold d-block mb-1">Brend</span>
                                                <small id="count_brand" style="font-size: 1.2rem;" class="text-success">
                                                    {{App\Models\Brand::where('user_id','=',auth()->id())->count()}}
                                                </small>
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
                                                <small style="font-size: 1.2rem;" id="count_product" class="text-success">
                                                    {{App\Models\Product::where('user_id','=',auth()->id())->count()}}
                                                </small>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-6 mb-4">
                                        <div class="card">
                                            <div class="card-body">
                                                <span class="fw-semibold d-block mb-1">Sifariş</span>
                                                <small id="count_order" style="font-size: 1.2rem;" class="text-success">
                                                    {{App\Models\Order::where('user_id','=',auth()->id())->count()}}
                                                </small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 col-md-8 col-lg-4 order-3 order-md-2">
                                <div class="row">
                                    @php($buy = 0)
                                    @php($sell = 0)
                                    @php($count = 0)
                                    @php($profit = 0)
                        
                                    @foreach($product_brand as $p)
                                    @php($profit = (($p->sell - $p->buy)*$p->count)+$profit)
                                    @endforeach
                                    <div class="col-6 mb-4">
                                        <div class="card">
                                            <div class="card-body">
                                                <span class="fw-semibold d-block mb-1">Qazanc</span>
                                                <small id="total_profit" style="font-size: 1.2rem;"
                                                    class="text-success">{{$profit}}</small>
                                            </div>
                                        </div>
                                    </div>
                                    @php($profit_new = 0)
                        
                                    @foreach ($orders_data as $profit_now)
                                    @if($profit_now->confirm==1)
                                    @php($profit_new = (($profit_now->sell - $profit_now->buy)*$profit_now->order_count)+$profit_new)
                                    @endif
                                    @endforeach
                                    <div class="col-6 mb-4">
                                        <div class="card">
                                            <div class="card-body">
                                                <span class="fw-semibold d-block mb-1">Cari qazanc</span>
                                                <small id="current-profit" style="font-size: 1.2rem;"
                                                    class="text-success">{{$profit_new}}</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            @yield('content')
                        </div>
                    </div>
                    <footer class="content-footer footer bg-footer-theme">
                        <div class="container-xxl d-flex flex-wrap justify-content-center py-2 flex-md-row flex-column">
                            <div class="mb-2 mb-md-0">
                                ©
                                <script>
                                    document.write(new Date().getFullYear());

                                </script>
                                Bəybala Muxtarov
                                <a href="https://themeselection.com" target="_blank"
                                    class="footer-link fw-bolder">Anbar</a>
                            </div>
                        </div>
                    </footer>
                    <div class="content-backdrop fade"></div>
                </div>
            </div>
        </div>
        <div class="layout-overlay layout-menu-toggle"></div>
    </div>

    <script src="{{url('public/assets/vendor/libs/popper/popper.js')}}"></script>
    <script src="{{url('public/assets/vendor/js/bootstrap.js')}}"></script>
    <script src="{{url('public/assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js')}}"></script>
    <script src="{{url('public/assets/vendor/js/menu.js')}}"></script>
    <script src="{{url('public/assets/vendor/libs/apex-charts/apexcharts.js')}}"></script>
    <script src="{{url('public/assets/js/main.js')}}"></script>
    <script src="{{url('public/assets/js/dashboards-analytics.js')}}"></script>
    <script async defer src="https://buttons.github.io/buttons.js"></script>
    <script>
       /*  $('#datatable-ajax-crud').ready(function () {
            
            setInterval(function () {
                brands = $("#brands").val();
                $('#count_brand').html(brands)

                clients = $("#client").val();
                $('#count_client').html(clients)

                products = $("#products").val();
                $('#count_product').html(products)

                orders = $("#orders").val();
                $('#count_order').html(orders)

                total_profit = $("#profit").val();
                $('#total_profit').html(total_profit)

                current_profit = $("#current_profit").val();
                $('#current-profit').html(current_profit)
            }, 1000) 
        }) */
    </script>
    @yield('scripts')
</body>

</html>
