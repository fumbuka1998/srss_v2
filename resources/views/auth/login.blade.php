<!DOCTYPE html>
<html lang="zxx" id="login-page">

<head>
    <!-- The above 6 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="keyword" content="">
    <meta name="author" content="" />
    <!-- Page Title -->
    <title>Sign In | ACS</title>
    <!-- Main CSS -->
    <link href="{{ asset('assets/plugins/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/plugins/ionicons/css/ionicons.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/skin/skin-turquoise.css') }}" rel="stylesheet" id="style-colors">
    <link href="{{ asset('assets/css/app.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/css/style.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/plugins/font-awesome/css/font-awesome.min.css') }} " rel="stylesheet" />






    <!-- Favicon -->
    <link rel="icon" sty href="{{ asset('assets/icons/sbrt-logo.ico') }}" type="image/x-icon">

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn"t work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
      <![endif]-->

    <style>
        .header-logo {
            height: 7rem;
            width: 8rem;
        }
    </style>

</head>

<body class="card-img-2">
    <!--================================-->
    <!-- User Singin Start -->
    <!--================================-->
    <div class="mg-y-120">
        <div class="card mx-auto wd-300 text-center pd-25 shadow-3">
            {{-- DUA  --}}
            {{-- <p>
                "Kwa Jina la Mwenyezi Mungu, Mwingi wa Rehema, Mwenye kurehemu."
                "Sifa njema zote ni za Mwenyezi Mungu, Mola Mlezi wa walimwengu wote."
                "Mwingi wa Rehema, Mwenye kurehemu."
                "Bwana wa Siku ya Hukumu."
                "Wewe (peke yake) tunakuabudu na Kwako (pekee) tunakuomba msaada."
                "Utuongoze (Ewe Mola) kwenye Njia Iliyo Nyooka."
                "Njia ya wale uliowaneemesha, sio (njia) ya waliokasirikiwa, wala (waliopotea) waliopotea."
            </p> --}}
            <h4 class="card-title mt-3 text-center">
                <img style="margin:auto;" class="header-logo" src="{{ asset('assets/logo/sbrt_logo.gif') }}"
                    alt="" viewBox="0 0 16 16" height="70">
            </h4>

            <form action="{{ route('login') }}" method="POST">
                @csrf
                <div class="form-group input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text pd-x-9 text-muted"> <i class="fa fa-user"></i> </span>
                    </div>
                    <input class="form-control form-control-sm" id="username" name="username" placeholder="Username"
                        type="text">
                </div>
                <div class="form-group input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text text-muted"> <i class="fa fa-lock"></i> </span>
                    </div>
                    <input class="form-control form-control-sm" name="password" placeholder="Create password"
                        type="password">
                </div>
                <p class="text-center"><a href="#">Forget Password?</a></p>

                <div class="form-group">
                    <button type="submit" class="btn btn-info btn-block tx-13 hover-white"> Login </button>
                </div>
                {{-- <p class="text-center">Don't have an account? <a href="page-singup.html">Create Account</a> </p> --}}
            </form>
        </div>
    </div>
    <!--/ User Singin End -->
    <!--================================-->
    <!-- Footer Script -->
    <!--================================-->
    <script src="{{ asset('assets/plugins/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/jquery-ui/jquery-ui.js') }}"></script>
    <script src="{{ asset('assets/plugins/popper/popper.js') }}"></script>
    <script src="{{ asset('assets/plugins/bootstrap/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/pace/pace.min.js') }}"></script>
    <script src="{{ asset('assets/js/jquery.slimscroll.min.js') }}"></script>
    <script src="{{ asset('assets/js/custom.js') }}"></script>
    <script src="{{ asset('assets/plugins/f6/js/all.min.js') }}"></script>
    <script>
        let inputElem = document.querySelector("#login-page");
        window.addEventListener('load', function(e) {
            inputElem.focus();
        });
    </script>
</body>

</html>
