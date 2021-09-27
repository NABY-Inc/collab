<!doctype html>
<html lang="en" dir="ltr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" href="favicon.ico" type="image/x-icon"/>

    <title>Project Colaboration System :: Login</title>

    <!-- Bootstrap Core and vandor -->
    <link rel="stylesheet" href="assets/plugins/bootstrap/css/bootstrap.min.css" />

    <!-- Core css -->
    <link rel="stylesheet" href="assets/css/main.css"/>
    <link rel="stylesheet" href="assets/css/theme1.css"/>
    <link rel="stylesheet" href="assets/css/font-awesome.min.css"/>

</head>
<body class="font-montserrat">

<div class="auth">
    <div class="auth_left">
        <div class="card">
            <div class="text-center mb-2">
                {{--<a class="header-brand" href="index.html"><i class="fa fa-soccer-ball-o brand-logo"></i></a>--}}
            </div>
            <form method="POST" action="{{ route('login') }}">
                @csrf
                <div class="card-body">
                    <h3>Work Better, Together</h3>
                    <p style="color: #333; font-size: 14px;">The easiest way to get your work done.
                        Share tasks, projects and files with anyone you work with.</p>
                    <div class="form-group">
                        <label class="form-label">Email</label>
                        <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" aria-describedby="emailHelp" placeholder="example@example.com" value="{{ old('email') }}" required autocomplete="email" autofocus>

                        @error('email')
                        <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                        @enderror

                    </div>

                    <div class="form-group">
                        <label class="form-label">Password</label>
                        <input type="password" class="form-control @error('password') is-invalid @enderror" name="password" placeholder="******" required autocomplete="current-password">

                        @error('password')
                        <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                        @enderror

                    </div>

                    {{-- <div class="form-group">
                        <label class="custom-control custom-checkbox">
                            <input type="checkbox" class="custom-control-input" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}/>
                            <span class="custom-control-label">Remember me</span>
                        </label>
                    </div> --}}
                    <div class="form-footer">
                        <button type="submit" class="btn btn-primary btn-block" >Sign in</button>
                    </div>
                </div>
            </form>
            <div class="text-center text-muted">
                {{--Don't have account yet? <a href="register.html">Sign up</a>--}}
            </div>
        </div>
    </div>
    <div class="auth_right full_img"></div>
</div>

<script src="assets/bundles/lib.vendor.bundle.js"></script>
<script src="assets/js/core.js"></script>
</body>

</html>
