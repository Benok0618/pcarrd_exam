<x-layout title="New category">
    <div class="mx-auto max-w-lg">
        <h1 class="mb-1 text-xl font-semibold text-slate-900">New category</h1>
        <p class="mb-6 text-sm text-slate-500">Add a research area for proposals.</p>

        <form method="POST" action="{{ route('categories.store') }}" class="card space-y-5 p-6">
            @csrf

            <div>
                <label for="name" class="label">Name</label>
                <input id="name" type="text" name="name" value="{{ old('name') }}" class="input" required>
                @error('name') <p class="error">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="description" class="label">Description</label>
                <textarea id="description" name="description" rows="3" class="input">{{ old('description') }}</textarea>
                @error('description') <p class="error">{{ $message }}</p> @enderror
            </div>

            <div class="flex items-center gap-3 pt-2">
                <button type="submit" class="btn btn-primary">Save category</button>
                <a href="{{ route('categories.index') }}" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</x-layout>
