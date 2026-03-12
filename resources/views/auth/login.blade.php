<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login - Inventory System</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:400,600,700" rel="stylesheet">
    
    <style>
        body {
            background-color: #ecf0f5; /* Abu muda (sama kayak dashboard) */
            font-family: 'Open Sans', sans-serif;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .login-box {
            width: 100%;
            max-width: 400px;
            padding: 15px;
        }

        .panel-login {
            background: #fff;
            border: none;
            border-radius: 8px; /* Sudut tumpul */
            box-shadow: 0 10px 30px rgba(0,0,0,0.1); /* Shadow halus */
            padding: 30px;
        }

        .login-header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 1px solid #eee;
            padding-bottom: 20px;
        }

        .login-title {
            font-size: 24px;
            font-weight: 800;
            color: #333;
            letter-spacing: 1px;
            margin: 0;
        }

        .login-subtitle {
            font-size: 12px;
            color: #f39c12; /* Oranye */
            font-weight: 700;
            letter-spacing: 2px;
            margin-top: 5px;
            text-transform: uppercase;
        }

        .form-control {
            height: 45px;
            border-radius: 4px;
            border: 1px solid #ddd;
            background: #fdfdfd;
            box-shadow: none;
            font-size: 14px;
        }

        .form-control:focus {
            border-color: #f39c12;
            background: #fff;
        }

        .input-group-addon {
            background: #f9f9f9;
            border: 1px solid #ddd;
            border-right: none;
            color: #777;
        }

        .btn-login {
            background-color: #f39c12;
            color: #fff;
            font-weight: bold;
            height: 45px;
            border-radius: 4px;
            font-size: 16px;
            transition: 0.3s;
            border: none;
            width: 100%;
            margin-top: 20px;
        }

        .btn-login:hover {
            background-color: #e67e22;
            color: #fff;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(243, 156, 18, 0.3);
        }

        .text-footer {
            text-align: center;
            margin-top: 20px;
            font-size: 12px;
            color: #999;
        }
    </style>
</head>
<body>

    <div class="login-box">
        <div class="panel-login">
            <div class="login-header">
                <div class="login-title">INVENTORY AGI</div>
                <div class="login-subtitle">SYSTEM V1.0</div>
            </div>

            <form method="POST" action="{{ route('login') }}">
                {{ csrf_field() }}

                @if ($errors->has('email'))
                    <div class="alert alert-danger text-center" style="font-size: 12px; padding: 10px;">
                        {{ $errors->first('email') }}
                    </div>
                @endif

                <div class="form-group">
                    <label class="text-muted" style="font-size:12px; font-weight:600;">EMAIL</label>
                    <div class="input-group">
                        <span class="input-group-addon"><i class="glyphicon glyphicon-envelope"></i></span>
                        <input type="email" name="email" class="form-control" placeholder="user@factory.com" required autofocus value="{{ old('email') }}">
                    </div>
                </div>

                <div class="form-group">
                    <label class="text-muted" style="font-size:12px; font-weight:600;">PASSWORD</label>
                    <div class="input-group">
                        <span class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></span>
                        <input type="password" name="password" class="form-control" placeholder="••••••••" required>
                    </div>
                </div>

                <button type="submit" class="btn btn-login">
                    MASUK SISTEM
                </button>
            </form>

            <div class="text-footer">
                &copy; {{ date('Y') }} PT. Asano Gear Indonesia. All rights reserved.
            </div>
        </div>
    </div>

</body>
</html>