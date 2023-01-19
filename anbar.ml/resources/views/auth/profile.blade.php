<!DOCTYPE html>

<html lang="en" class="light-style layout-menu-fixed" dir="ltr" data-theme="theme-default" data-assets-path="../assets/"
    data-template="vertical-menu-template-free">
<head>
    <meta charset="utf-8" />
    <meta name="viewport"
        content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />
    <title>Profilim</title>
    <meta name="description" content="" />
    <link rel="icon" type="image/x-icon" href="{{url('public/assets/img/favicon/favicon.ico')}}" />
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
        href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap"
        rel="stylesheet" />
    <link rel="stylesheet" href="{{url('public/assets/vendor/fonts/boxicons.css')}}" />
    <link rel="stylesheet" href="{{url('public/assets/vendor/css/core.css')}}" class="template-customizer-core-css" />
    <link rel="stylesheet" href="{{url('public/assets/vendor/css/theme-default.css')}}" class="template-customizer-theme-css" />
    <link rel="stylesheet" href="{{url('public/assets/css/demo.css')}}" />
    <script src="{{url('public/assets/js/config.js')}}"></script>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.9.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="{{url('public/assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css')}}" />
    <script src="{{url('public/assets/vendor/js/helpers.js')}}"></script>
    <script src="{{url('public/assets/js/config.js')}}"></script>
</head>

<body>
    <div class="layout-wrapper layout-content-navbar">
        <div class="layout-container">
            <aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
                <div class="app-brand demo">
                    <a href="/" class="app-brand-link">
                        <span class="app-brand-text demo menu-text fw-bolder ms-2">anbar</span>
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
                        <a href="/order" class="menu-link">
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
                            <!-- User -->
                            <li class="nav-item navbar-dropdown dropdown-user dropdown">
                                <a class="nav-link dropdown-toggle hide-arrow" href="javascript:void(0);"
                                    data-bs-toggle="dropdown">
                                    <div class="avatar avatar-online">
                                        <img src="{{auth()->user()->logo}}" alt
                                        class="rounded-circle" />
                                    </div>
                                </a>
                                <ul class="dropdown-menu dropdown-menu-end">
                                    <li>
                                        <a class="dropdown-item" href="#">
                                            <div class="d-flex">
                                                <div class="flex-shrink-0 me-3">
                                                    <div class="avatar avatar-online">
                                                        <img src="{{auth()->user()->logo}}" alt
                                                            class="rounded-circle" />
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
                        <h4 class="fw-bold py-3 mb-4">Profilim </h4>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="card mb-4">
                                    <hr class="my-0" />
                                    <div class="card-body">
                                        <form id="main_form" action="profile_update" method="POST"
                                            enctype="multipart/form-data" onsubmit="return false">
                                            @csrf
                                            <div class="row">
                                                <div class="card-body">
                                                    <div class="d-flex align-items-start align-items-sm-center gap-4">
                                                        <img src="{{auth()->user()->logo}}" alt="logo"
                                                            class="d-block rounded" height="100" width="100"
                                                            id="uploadedAvatar" />
                                                        <div class="button-wrapper">
                                                            <label for="upload" class="btn btn-primary me-2 mb-4"
                                                                tabindex="0">
                                                                <span class="d-none d-sm-block">Şəkil yüklə</span>
                                                                <i class="bx bx-upload d-block d-sm-none"></i>
                                                                <input type="file" name="logo" id="upload"
                                                                    class="account-file-input" hidden
                                                                    accept="image/png, image/jpeg" />
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="mb-3 col-md-6">
                                                    <label for="name" class="form-label">İstifadəçi adı</label>
                                                    <input class="form-control" type="text" id="name" name="name"
                                                        value="{{auth()->user()->name}}" autofocus />
                                                    <span class="text-danger error-text name_error"></span>
                                                </div>

                                                <div class="mb-3 col-md-6">
                                                    <label for="email" class="form-label">E-mail</label>
                                                    <input class="form-control" type="email" id="email" name="email"
                                                        value="{{auth()->user()->email}}"/>
                                                    <span class="text-danger error-text email_error"></span>
                                                </div>

                                                <div class="mb-3 col-md-6">
                                                    <label for="password" class="form-label">Cari parol</label>
                                                    <input class="form-control" type="password" id="password"
                                                        name="password" placeholder="........" />
                                                    <span class="text-danger error-text password_error"></span>
                                                </div>

                                                <div class="mb-3 col-md-6">
                                                    <label for="password_new" class="form-label">Yeni parol</label>
                                                    <input class="form-control" type="password" id="password_new"
                                                        name="password_new" placeholder="........" />
                                                    <span class="text-danger error-text password_new_error"></span>
                                                </div>

                                                <div class="mb-3 col-md-6">
                                                    <label for="password_confirmation" class="form-label">Təkrar
                                                        parol</label>
                                                    <input class="form-control" type="password"
                                                        id="password_confirmation" name="password_confirmation"
                                                        placeholder="........" />
                                                    <span class="text-danger error-text password_confirmation_error"></span>
                                                </div>
                                            </div>
                                            <div class="mt-2">
                                                <button type="submit" class="btn btn-primary me-2">Yadda saxla</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <footer class="content-footer footer bg-footer-theme">
                        <div
                            class="container-xxl d-flex flex-wrap justify-content-center py-2 flex-md-row flex-column">
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
    <script src="{{url('public/assets/vendor/libs/jquery/jquery.js')}}"></script>
    <script src="{{url('public/assets/vendor/libs/popper/popper.js')}}"></script>
    <script src="{{url('public/assets/vendor/js/bootstrap.js')}}"></script>
    <script src="{{url('public/assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js')}}"></script>
    <script src="{{url('public/assets/vendor/js/menu.js')}}"></script>
    <script src="{{url('public/assets/js/pages-account-settings-account.js')}}"></script>
    <script async defer src="https://buttons.github.io/buttons.js"></script>
    <script src="{{url('public/assets/js/profile.js')}}"></script>
</body>

</html>
