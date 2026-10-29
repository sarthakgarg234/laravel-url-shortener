<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dashboard | URL Shortener</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f8fafc; margin: 30px; }
        .panel { background: white; padding: 20px; margin-bottom: 20px; border-radius: 10px; }
        input, select, button { padding: 10px; margin: 5px 0; }
        input { max-width: 450px; width: 90%; }
        table { width: 100%; border-collapse: collapse; }
        th, td { text-align: left; padding: 10px; border-bottom: 1px solid #ddd; }
        button { cursor: pointer; }
        .success { color: green; }
    </style>
</head>
<body>
    <h1>URL Shortener Dashboard</h1>

    <div class="panel">
        <p>
            Welcome, <strong>{{ auth()->user()->name }}</strong>
            ({{ auth()->user()->role }})
        </p>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit">Logout</button>
        </form>
    </div>

    @if (session('success'))
        <p class="success">{{ session('success') }}</p>
    @endif

    @if (in_array(auth()->user()->role, ['Sales', 'Manager'], true))
        <div class="panel">
            <h2>Create Short URL</h2>
            <form method="POST" action="{{ route('short-urls.store') }}">
                @csrf
                <input type="url" name="original_url"
                       placeholder="https://example.com"
                       maxlength="2048" required>
                <button type="submit">Create</button>
            </form>
            @error('original_url')
                <p>{{ $message }}</p>
            @enderror
        </div>
    @endif

    @if (in_array(auth()->user()->role, ['SuperAdmin', 'Admin'], true))
        <div class="panel">
            <h2>Invite User</h2>
            <form method="POST" action="{{ route('invitations.store') }}">
                @csrf
                <input type="email" name="email" placeholder="Email" required>
                <select name="role" required>
                    @if (auth()->user()->role === 'SuperAdmin')
                        <option value="Member">Member</option>
                        <option value="Sales">Sales</option>
                        <option value="Manager">Manager</option>
                    @else
                        <option value="Sales">Sales</option>
                        <option value="Manager">Manager</option>
                    @endif
                </select>
                <button type="submit">Create Invitation</button>
            </form>
            @error('email')
                <p>{{ $message }}</p>
            @enderror
            @error('role')
                <p>{{ $message }}</p>
            @enderror
        </div>
    @endif

    <div class="panel">
        <h2>Short URLs</h2>
        <table>
            <thead>
                <tr>
                    <th>Code</th>
                    <th>Original URL</th>
                    <th>Created By</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($urls as $url)
                    <tr>
                        <td>{{ $url->code }}</td>
                        <td>{{ $url->original_url }}</td>
                        <td>{{ $url->user?->name ?? 'Unknown' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3">No URLs available for your account.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        {{ $urls->links() }}
    </div>
</body>
</html>