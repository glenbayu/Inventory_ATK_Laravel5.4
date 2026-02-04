<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login - Inventory System</title>

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <link href="https://fonts.googleapis.com/css?family=Roboto:400,700|Roboto+Mono:400,700" rel="stylesheet">

    <style>
        /* --- INDUSTRIAL THEME CSS --- */
        body {
            background-color: #2c3e50; /* Biru Tua Gelap (Warna Gudang Malam) */
            background-image: repeating-linear-gradient(
                45deg,
                #2c3e50,
                #2c3e50 10px,
                #34495e 10px,
                #34495e 20px
            ); /* Efek garis-garis halus */
            font-family: 'Roboto', sans-serif;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .login-container {
            width: 100%;
            max-width: 400px;
            padding: 15px;
        }

        .panel-industrial {
            border: 0;
            border-radius: 0; /* Kotak tajam */
            box-shadow: 15px 15px 0px rgba(0,0,0,0.5); /* Bayangan kasar */
            background: #f8f9fa;
        }

        .panel-heading-industrial {
            background-color: #222;
            color: #f39c12; /* Kuning Safety */
            padding: 20px;
            text-align: center;
            border-bottom: 5px solid #f39c12;
            font-family: 'Roboto Mono', monospace;
            letter-spacing: 2px;
            font-weight: bold;
        }

        .form-control {
            border-radius: 0;
            border: 2px solid #555;
            height: 45px;
            font-family: 'Roboto Mono', monospace;
            background: #fff;
        }
        
        .form-control:focus {
            border-color: #f39c12;
            box-shadow: none;
        }

        .btn-industrial {
            background-color: #f39c12;
            color: #000;
            font-weight: bold;
            text-transform: uppercase;
            border: 3px solid #000;
            border-radius: 0;
            width: 100%;
            padding: 12px;
            font-size: 16px;
            transition: all 0.2s;
        }

        .btn-industrial:hover {
            background-color: #000;
            color: #f39c12;
            border-color: #f39c12;
            transform: translateY(-2px); /* Efek pencet */
        }

        .footer-text {
            text-align: center;
            margin-top: 20px;
            color: #ccc;
            font-family: 'Roboto Mono', monospace;
            font-size: 12px;
        }
    </style>
</head>
<body>

<div class="login-container">
    <div class="panel panel-industrial">
        <div class="panel-heading-industrial">
            <i class="glyphicon glyphicon-lock"></i> SECURE ACCESS
        </div>
        <div class="panel-body" style="padding: 30px;">
            
            <form class="form-horizontal" method="POST" action="{{ route('login') }}">
                {{ csrf_field() }}

                <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                    <label style="font-weight:bold; text-transform:uppercase; font-size:12px;">ID / Email</label>
                    <div class="input-group">
                        <span class="input-group-addon" style="border-radius:0; background:#ddd; border:2px solid #555; border-right:0;"><i class="glyphicon glyphicon-user"></i></span>
                        <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}" required autofocus placeholder="user@factory.com">
                    </div>
                    @if ($errors->has('email'))
                        <span class="help-block text-danger"><strong>{{ $errors->first('email') }}</strong></span>
                    @endif
                </div>

                <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}" style="margin-top: 20px;">
                    <label style="font-weight:bold; text-transform:uppercase; font-size:12px;">Password</label>
                    <div class="input-group">
                        <span class="input-group-addon" style="border-radius:0; background:#ddd; border:2px solid #555; border-right:0;"><i class="glyphicon glyphicon-asterisk"></i></span>
                        <input id="password" type="password" class="form-control" name="password" required placeholder="********">
                    </div>
                    @if ($errors->has('password'))
                        <span class="help-block text-danger"><strong>{{ $errors->first('password') }}</strong></span>
                    @endif
                </div>

                <div class="form-group" style="margin-top: 30px; margin-bottom: 0;">
                    <button type="submit" class="btn btn-industrial">
                        MASUK SISTEM &rarr;
                    </button>
                </div>

            </form>
        </div>
    </div>
    
    <div class="footer-text">
        INVENTORY SYSTEM v1.0<br>
        <span style="color: #f39c12">UNAUTHORIZED ACCESS IS PROHIBITED</span>
    </div>
</div>

</body>
</html>