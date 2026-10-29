<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Accept Invitation</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f3f4f6;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
        }
        .card {
            background: white;
            padding: 30px;
            border-radius: 12px;
            width: 100%;
            max-width: 400px;
            box-shadow: 0 8px 30px #00000012;
        }
        input, button {
            box-sizing: border-box;
            width: 100%;
            padding: 12px;
            margin: 8px 0 16px;
            border: 1px solid #d1d5db;
            border-radius: 6px;
        }
        button {
            background: #2563eb;
            color: white;
            border: none;
            cursor: pointer;
        }
        .error { color: #dc2626; }
    </style>
</head>
<body>
<div class="card">
    <h2>Accept Your Invitation</h2>
    <p>Email: <strong>{{ $invitation->email }}</strong></p>
    <p>Assigned role: <strong>{{ $invitation->role }}</strong></p>

    @if ($errors->any())
        <div class="error">
            @foreach ($errors->all() as $error)
                <p>{{ $error }}</p>
            @endforeach
        </div>
    @endif

    <form method="POST" action="{{ route('invitations.accept', $invitation->token) }}">
        @csrf

        <label for="name">Full Name</label>
        <input id="name" type="text" name="name"
               value="{{ old('name') }}" required maxlength="255">

        <label for="password">Password</label>
        <input id="password" type="password" name="password" required minlength="8">

        <label for="password_confirmation">Confirm Password</label>
        <input id="password_confirmation" type="password"
               name="password_confirmation" required minlength="8">

        <button type="submit">Create Account</button>
    </form>
</div>
</body>
</html>
