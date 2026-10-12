<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Log in &middot; DOST&ndash;PCAARRD Proposals</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="flex min-h-screen items-center justify-center bg-slate-50 px-4">
    <div class="w-full max-w-sm">
        <div class="mb-6 text-center">
            <h1 class="text-lg font-semibold text-slate-900">DOST&ndash;PCAARRD Proposals</h1>
            <p class="mt-1 text-sm text-slate-500">Sign in to continue</p>
        </div>

        <form method="POST" action="{{ route('login') }}" class="card space-y-4 p-6">
            @csrf

            <div>
                <label for="email" class="label">Email or username</label>
                <input id="email" type="text" name="email" value="{{ old('email') }}"
                    class="input" autofocus required>
                @error('email')
                    <p class="error">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="password" class="label">Password</label>
                <input id="password" type="password" name="password" class="input" required>
                @error('password')
                    <p class="error">{{ $message }}</p>
                @enderror
            </div>

            <label class="flex items-center gap-2 text-sm text-slate-600">
                <input type="checkbox" name="remember" value="1" class="rounded border-slate-300">
                Remember me
            </label>

            <button type="submit" class="btn btn-primary w-full">Log in</button>
        </form>

        <div class="mt-4 rounded-md border border-slate-200 bg-white px-4 py-3 text-xs text-slate-500">
            <p class="font-medium text-slate-600">Demo accounts</p>
            <p class="mt-1">Admin &mdash; admin / admin</p>
            <p>Reviewer &mdash; reviewer@example.com / password</p>
            <p>Proponent &mdash; proponent@example.com / password</p>
        </div>
    </div>
</body>
</html>
