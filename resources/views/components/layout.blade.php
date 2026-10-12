<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? 'Proposal Management System' }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
    <header class="border-b border-slate-200 bg-white">
        <div class="mx-auto flex max-w-5xl items-center justify-between px-4 py-3">
            <div class="flex items-center gap-6">
                <a href="{{ route('proposals.index') }}" class="text-sm font-semibold text-slate-900">
                    DOST&ndash;PCAARRD Proposals
                </a>
                @auth
                    <nav class="hidden items-center gap-4 text-sm text-slate-600 sm:flex">
                        <a href="{{ route('proposals.index') }}" class="hover:text-slate-900">Proposals</a>
                        <a href="{{ route('categories.index') }}" class="hover:text-slate-900">Categories</a>
                    </nav>
                @endauth
            </div>

            @auth
                <div class="flex items-center gap-3 text-sm">
                    <span class="text-slate-600">
                        {{ auth()->user()->name }}
                        <span class="ml-1 text-xs uppercase tracking-wide text-slate-400">
                            {{ auth()->user()->getRoleNames()->first() }}
                        </span>
                    </span>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="btn btn-secondary">Log out</button>
                    </form>
                </div>
            @endauth
        </div>
    </header>

    <main class="mx-auto max-w-5xl px-4 py-8">
        @if (session('success'))
            <div class="mb-4 rounded-md border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800">
                {{ session('success') }}
            </div>
        @endif

        {{ $slot }}
    </main>
</body>
</html>
