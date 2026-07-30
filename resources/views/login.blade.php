<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>JADWA</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.rtl.min.css" rel="stylesheet">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="icon" href="{{ asset('upload/logo.jpeg') }}" type="image/x-icon">
    <style>

        body {
            background: linear-gradient(135deg,#0dcaf0,#0d6efd);
            min-height:100vh;
            display:flex;
            justify-content:center;
            align-items:center;
            font-family: "Tajawal", sans-serif;
        }


        .login-card {

            width:400px;
            border-radius:20px;
            box-shadow:0 10px 30px rgba(0,0,0,.2);
            background:white;
            padding:35px;

        }


        .logo {

            width:80px;
            height:80px;
            background:#0dcaf0;
            color:white;
            border-radius:50%;
            display:flex;
            justify-content:center;
            align-items:center;
            margin:auto;
            font-size:35px;

        }


        .btn-login {

            background:#0d6efd;
            color:white;
            border-radius:12px;
            padding:12px;
            font-weight:bold;

        }


        .btn-login:hover {

            background:#084298;
            color:white;

        }


    </style>

</head>


<body>


<div class="login-card">


    <div class="logo mb-3">
        <i class="bi bi-bus-front-fill"></i>
    </div>


    <h3 class="text-center mb-4">
        نظام إدارة الرحلات
    </h3>



    @if(session('error'))

        <div class="alert alert-danger text-center">
            {{ session('error') }}
        </div>

    @endif



    <form action="{{ route('login') }}" method="POST">

        @csrf


        <div class="mb-3">

            <label class="form-label">
                اسم المستخدم
            </label>


            <div class="input-group">

                <span class="input-group-text">
                    <i class="bi bi-person"></i>
                </span>


                <input 
                    type="text"
                    name="username"
                    class="form-control"
                    placeholder="ادخل اسم المستخدم"
                    required
                >

            </div>

        </div>



        <div class="mb-3">

            <label class="form-label">
                كلمة المرور
            </label>


            <div class="input-group">

                <span class="input-group-text">
                    <i class="bi bi-lock"></i>
                </span>


                <input 
                    type="password"
                    name="password"
                    class="form-control"
                    placeholder="ادخل كلمة المرور"
                    required
                >

            </div>

        </div>




        <button class="btn btn-login w-100 mt-3">

            <i class="bi bi-box-arrow-in-right"></i>
            تسجيل الدخول

        </button>


    </form>


</div>



</body>

</html>