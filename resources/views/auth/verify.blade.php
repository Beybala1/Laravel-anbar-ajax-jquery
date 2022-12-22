<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">
<title>Təsdiq et</title>
<div style="justify-content: center; align-items:center;" class="container">
    <div style="margin: 200px 0" class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Təsdiq edin</div>

                <div class="card-body">
                    @if (session('resent'))
                        <div class="alert alert-success" role="alert">
                            {{ __('Təsdiq mesajı emailinizə yollandı.') }}
                        </div>
                    @endif
                    Anbara giriş edə bilmək üçün emailinizə gələn mesajı təsdiq edin.
                    Emailinizə mesaj gəlmiyib isə, aşağıda olan təsdiq mesajı göndər linkinə basın.<br>
                    <form class="d-inline" method="POST" action="{{ route('verification.resend') }}">
                        @csrf
                        <button type="submit" class="btn btn-link p-0 m-0 align-baseline">{{ __('Təsdiq mesajı yolla') }}</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

