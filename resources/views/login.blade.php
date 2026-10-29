<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login | URL Shortener</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f1f5f9;
            display: grid;
            place-items: center;
            min-height: 100vh;
            margin: 0;
        }
        .card {
            background: white;
            padding: 32px;
            border-radius: 12px;
            width: min(340px, 80vw);
            box-shadow: 0 8px 30px #0001;
        }
        input, button {
            box-sizing: border-box;
            width: 100%;
            padding: 12px;
            margin-top: 12px;
            border-radius: 6px;
        }
        input { border: 1px solid #cbd5e1; }
        button {
            background: #2563eb;
            color: white;
            border: 0;
            cursor: pointer;
        }
        .error { color: #dc2626; }
    </style>
</head>
<body>
<div class="card">
    <h2>URL Shortener</h2>
    <p>Sign in to your account</p>

    @if ($errors->any())
        <p class="error">{{ $errors->first() }}</p>
    @endif

    <form method="POST" action="{{ route('login') }}">
        @csrf
        <input type="email" name="email"
               placeholder="Email address"
               value="{{ old('email') }}" required>

        <input type="password" name="password"
               placeholder="Password" required>

        <button type="submit">Login</button>
    </form>
</div>
</body>
</html>