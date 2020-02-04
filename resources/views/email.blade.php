<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Reset Password - Islamic Resource Hub</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
     <link rel="stylesheet" href="{{ asset('irh_assets/vendor/bootstrap/bootstrap.min.css') }}" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    <style>
        a,a:hover
        {
            text-decoration: none;
            color:#333;
        }

        body
        {
            background: linear-gradient(rgba(255, 205, 36, 0.5),rgba(51, 57, 61, 0.5)), url({{ asset('irh_assets/images/loginbg.jpg') }});
            background-position: center;
            background-size: cover;
            background-position-y: bottom;
            background-repeat: no-repeat;
            height: 100vh;
            overflow-x: hidden;
        }

        .container  
        {
            position: absolute;
            left: 0;
            right: 0;
            top: 10%;
        }

        .sign-in-wrapper
        {
            background:#fff;
            padding:80px;
            height: 620px;
        }

        .sign-up-wrapper
        {
            background: #2AABE4;
            opacity: 0.9;
            padding:120px;
            height: 550px;
            color:#fff;
        }

        #sign-up-container
        {
            position: absolute;
            width: 100%;
            right: 0;
            top: 6%;
        }

        .form-control {
            border: 1px solid #495057;
            border-radius: 1.25rem;
        }

        .btn.btn-login
        {
                border-radius: 1.25rem;
                background: #26a0d6;
                border: 1px solid #249cd2;
        }

        .btn.btn-yellow
        {
            border-radius: 1.25rem;
            background: #FFCD24;
            border: 1px solid #FFCD24;
            color:#333;
        }

        @media screen and (max-width: 546px)
        {
            #sign-up-container
            {
                position: relative;
            }

            .sign-in-wrapper, .sign-up-wrapper
            {
                padding: 60px;
            }
        }

        @media screen and (max-width: 1024px)
        {
            .sign-in-wrapper, .sign-up-wrapper
            {
                padding: 80px;
            }
        }

        @media screen and (max-width: 768px)
        {
            .sign-in-wrapper
            {
                padding: 50px;
                /*height: 374px;*/
            }

            .sign-up-wrapper
            {
                padding: 50px;
                /*height: 315px;*/
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="row">
            <div class="col-md-6 p-0" id="login-container">
                <div class="sign-in-wrapper">
                    <h4>Reset Password</h4>
                    <br>
                    <form action="{{ route('password.email') }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <input id="email" type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ old('email') }}" placeholder="Your Email .." required>

                            @if ($errors->has('email'))
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('email') }}</strong>
                                </span>
                            @endif
                        </div>
                        <div class="form-group">
                            <input type="submit" class="btn btn-primary btn-block btn-login" value="Send Password Reset Link">
                        </div>
                        <div class="form-group">
                             @if (session('status'))
                                <div class="alert alert-success" role="alert">
                                    {{ session('status') }}
                                </div>
                            @endif
                        </div>
                    </form>
                </div>
            </div>
            <div class="col-md-6 p-0" id="sign-up-container">
                <div class="sign-up-wrapper">
                    <h4>Sign In</h4>
                    <p>Sign In to Islamic Resource Hub</p>
                    <a href="{{ route('login') }}" class="btn btn-block btn-yellow">Sign In</a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>