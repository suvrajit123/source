<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>Privacy Policy - Islamic Resource Hub</title>
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
        background: linear-gradient(rgba(30, 169, 231, 0.5),rgba(51, 57, 61, 0.5)), url({{ asset('irh_assets/images/loginbg.jpg') }});
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
        top: 20%;
        }
        .sign-in-wrapper
        {
        background:#fff;
        padding:20px;
        height: auto;
        }
        .sign-up-wrapper
        {
        background: #2AABE4;
        opacity: 0.9;
        padding:20px;
        height: 450px;
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
        height: auto;
        }
        .sign-up-wrapper
        {
        padding: 50px;
        height: auto;
        }
        }
        </style>
    </head>
    <body>
         <div class="logo text-center pt-4">
             <a class="" href="{{ url('/') }}">
          <img src="{{ asset('irh_assets/images/irhsignika.png') }}" alt="" width="175" height="60">
        </a>
         </div>
        <div class="container">
            <div class="row">
                <div class="col-md-12 p-0" id="login-container">
                    <div class="sign-in-wrapper p-4">
                        <h4 class="text-center">Privacy Policy</h4>
                        <p class="py-3">
                            {!! $pp !!}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>