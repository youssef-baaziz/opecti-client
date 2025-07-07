<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;700&display=swap');
        body {
            font-family: 'Roboto', sans-serif;
            background: linear-gradient(135deg,rgb(194, 226, 235) 0%,rgb(44, 108, 186) 100%);
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            overflow: hidden;
            animation: backgroundAnimation 5s infinite alternate;
        }
        @keyframes backgroundAnimation {
            0% { background-position: 0% 50%; }
            100% { background-position: 100% 50%; }
        }
        .login-container {
            background: rgba(255, 255, 255, 0.8);
            padding: 3rem;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
            width: 100%;
            max-width: 450px;
            backdrop-filter: blur(10px);
            animation: containerAnimation 2s infinite alternate;
        }
        @keyframes containerAnimation {
            0% { transform: scale(1); }
            100% { transform: scale(1.05); }
        }
        .login-header {
            font-size: 2rem;
            margin-bottom: 2rem;
            text-align: center;
            color: #333;
            font-weight: 700;
        }
        .form-group {
            margin-bottom: 1.5rem;
        }
        label {
            display: block;
            margin-bottom: 0.5rem;
            color: #555;
            font-weight: 400;
        }
        input[type="email"],
        input[type="password"] {
            width: 100%;
            padding: 1rem;
            border: none;
            border-radius: 8px;
            font-size: 1rem;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            transition: box-shadow 0.3s ease;
        }
        input[type="email"]:focus,
        input[type="password"]:focus {
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
            outline: none;
        }
        .error-message {
            color: #e74c3c;
            font-size: 0.875rem;
            margin-top: 0.5rem;
        }
        .submit-btn {
            width: 100%;
            padding: 1rem;
            background-color: rgb(44, 108, 186);
            color: #fff;
            border: none;
            border-radius: 8px;
            font-size: 1.2rem;
            cursor: pointer;
            transition: background-color 0.3s ease, transform 0.3s ease, box-shadow 0.3s ease;
            font-weight: 700;
            animation: buttonAnimation 1s infinite alternate;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        @keyframes buttonAnimation {
            0% { transform: translateY(0); }
            100% { transform: translateY(-5px); }
        }
        .submit-btn:hover {
            background-color: #005bb5;
            box-shadow: 0 6px 10px rgba(0, 0, 0, 0.2);
            transform: translateY(-3px);
        }
        .register-link {
            text-align: center;
            margin-top: 2rem;
            font-size: 0.875rem;
            color: #555;
        }
        .register-link a {
            color: #0072ff;
            text-decoration: none;
            font-weight: 700;
        }
        .register-link a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-header">{{ __('Login') }}</div>
        <form method="POST" action="{{ route('login') }}">
            @csrf

            {{-- Email --}}
            <div class="form-group">
                <label for="email">{{ __('E-Mail Address') }}</label>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>
                @error('email')
                <div class="error-message">{{ $message }}</div>
                @enderror
            </div>

            {{-- Password --}}
            <div class="form-group">
                <label for="password">{{ __('Mot de passe') }}</label>
                <input id="password" type="password" name="password" required>
                @error('password')
                <div class="error-message">{{ $message }}</div>
                @enderror
            </div>

            {{-- Submit Button --}}
            <button type="submit" class="submit-btn">{{ __('Se Connecter') }}</button>

            <!-- {{-- Register Link --}}
            @if (Route::has('register'))
            <div class="register-link">
                <small>{{ __("Don't have an account?") }} <a href="{{ route('register') }}">{{ __('Register') }}</a></small>
            </div>
            @endif -->
        </form>
    </div>
</body>
</html>
