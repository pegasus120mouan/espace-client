<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>OVL Boutiques | Connexion</title>

    <link rel="icon" href="{{ asset('img/favicon.ico') }}" type="image/x-icon">
    <link rel="shortcut icon" href="{{ asset('img/favicon.ico') }}" type="image/x-icon">

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        :root {
            --primary-color: #799659;
            --primary-dark: #5a7342;
        }

        body {
            min-height: 100vh;
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .login-card {
            background: #fff;
            border-radius: 16px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.15);
            width: 100%;
            max-width: 420px;
            overflow: hidden;
        }

        .login-header {
            text-align: center;
            padding: 30px 30px 20px;
        }

        .login-header img {
            width: 70px;
            height: 70px;
            object-fit: cover;
            border-radius: 50%;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            margin-bottom: 15px;
        }

        .login-header h2 {
            font-weight: 700;
            color: #333;
            margin-bottom: 5px;
        }

        .login-header p {
            color: #6c757d;
            font-size: 14px;
        }

        .login-body {
            padding: 0 30px 30px;
        }

        .form-floating {
            position: relative;
            margin-bottom: 16px;
        }

        .form-floating .form-control {
            border-radius: 50px;
            padding-left: 45px;
            height: 50px;
            border: 1px solid #e0e0e0;
        }

        .form-floating .form-control:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(121, 150, 89, 0.15);
        }

        .form-floating label {
            padding-left: 45px;
        }

        .input-icon {
            position: absolute;
            left: 18px;
            top: 50%;
            transform: translateY(-50%);
            color: #8b96a5;
            z-index: 5;
        }

        .password-toggle {
            position: absolute;
            right: 18px;
            top: 50%;
            transform: translateY(-50%);
            color: #8b96a5;
            cursor: pointer;
            background: none;
            border: none;
            z-index: 5;
        }

        .password-toggle:hover {
            color: var(--primary-color);
        }

        .btn-login {
            background: var(--primary-color);
            border: none;
            border-radius: 50px;
            height: 50px;
            font-weight: 600;
            font-size: 16px;
            transition: all 0.3s ease;
        }

        .btn-login:hover {
            background: var(--primary-dark);
            transform: translateY(-2px);
            box-shadow: 0 5px 20px rgba(121, 150, 89, 0.3);
        }

        .form-check-input:checked {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }

        .alert {
            border-radius: 10px;
            font-size: 14px;
        }

        .login-footer {
            text-align: center;
            padding: 20px;
            background: #f8f9fa;
            font-size: 12px;
            color: #6c757d;
        }
    </style>
</head>
<body>

<div class="login-card">
    <div class="login-header">
        <img src="{{ asset('img/logo.png') }}" alt="OVL" onerror="this.src='https://via.placeholder.com/70'">
        <h2>Espace Boutique</h2>
        <p>Connectez-vous pour gérer vos commandes</p>
    </div>

    <div class="login-body">
        @if(session('success'))
            <div class="alert alert-success" role="alert">
                <i class="fas fa-check-circle me-2"></i>
                {{ session('success') }}
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger" role="alert">
                <i class="fas fa-exclamation-triangle me-2"></i>
                @foreach($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            </div>
        @endif

        <form action="{{ route('login.post') }}" method="POST">
            @csrf

            <div class="form-floating">
                <i class="fas fa-user input-icon"></i>
                <input type="text" name="login" id="login" class="form-control @error('login') is-invalid @enderror" placeholder="Login" value="{{ old('login') }}" required autofocus>
                <label for="login">Login</label>
            </div>

            <div class="form-floating">
                <i class="fas fa-lock input-icon"></i>
                <input type="password" name="password" id="password" class="form-control @error('password') is-invalid @enderror" placeholder="Mot de passe" required>
                <label for="password">Mot de passe</label>
                <button type="button" class="password-toggle" id="togglePassword">
                    <i class="fas fa-eye" id="eyeIcon"></i>
                </button>
            </div>

            <div class="d-flex justify-content-between align-items-center mb-4">
                <div class="form-check">
                    <input type="checkbox" class="form-check-input" id="remember" name="remember">
                    <label class="form-check-label" for="remember">Se souvenir</label>
                </div>
            </div>

            <button type="submit" class="btn btn-primary btn-login w-100">
                <i class="fas fa-sign-in-alt me-2"></i>Connexion
            </button>
        </form>
    </div>

    <div class="login-footer">
        &copy; {{ date('Y') }} OVL Delivery - Espace Boutiques
    </div>
</div>

<script>
(function() {
    var toggleBtn = document.getElementById('togglePassword');
    var passwordInput = document.getElementById('password');
    var eyeIcon = document.getElementById('eyeIcon');

    if (toggleBtn && passwordInput && eyeIcon) {
        toggleBtn.addEventListener('click', function() {
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                eyeIcon.classList.remove('fa-eye');
                eyeIcon.classList.add('fa-eye-slash');
            } else {
                passwordInput.type = 'password';
                eyeIcon.classList.remove('fa-eye-slash');
                eyeIcon.classList.add('fa-eye');
            }
        });
    }

    var loginInput = document.getElementById('login');
    var rememberCheckbox = document.getElementById('remember');

    if (localStorage.getItem('ovl_boutique_remember') === 'true') {
        var savedLogin = localStorage.getItem('ovl_boutique_login');
        if (savedLogin && loginInput) {
            loginInput.value = savedLogin;
        }
        if (rememberCheckbox) {
            rememberCheckbox.checked = true;
        }
    }

    var form = document.querySelector('form');
    if (form) {
        form.addEventListener('submit', function() {
            if (rememberCheckbox && rememberCheckbox.checked) {
                localStorage.setItem('ovl_boutique_remember', 'true');
                localStorage.setItem('ovl_boutique_login', loginInput ? loginInput.value : '');
            } else {
                localStorage.removeItem('ovl_boutique_remember');
                localStorage.removeItem('ovl_boutique_login');
            }
        });
    }
})();
</script>

</body>
</html>
