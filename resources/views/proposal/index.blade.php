<x-layout title="Proposals">
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h1 class="text-xl font-semibold text-slate-900">Proposals</h1>
            <p class="text-sm text-slate-500">
                {{ auth()->user()->hasRole('Reviewer') ? 'All submitted proposals' : 'Proposals you submitted' }}
            </p>
        </div>
        @hasanyrole('Proponent|Admin')
            <a href="{{ route('proposals.create') }}" class="btn btn-primary">New proposal</a>
        @endhasanyrole
    </div>

    <div class="card overflow-hidden">
        <table class="w-full text-left text-sm">
            <thead class="border-b border-slate-200 bg-slate-50 text-xs uppercase tracking-wide text-slate-500">
                <tr>
                    <th class="px-4 py-3 font-medium">Title</th>
                    <th class="px-4 py-3 font-medium">Category</th>
                    <th class="px-4 py-3 font-medium">Status</th>
                    <th class="px-4 py-3 font-medium">Budget</th>
                    <th class="px-4 py-3 font-medium text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($proposals as $proposal)
                    <tr class="hover:bg-slate-50">
                        <td class="px-4 py-3 font-medium text-slate-800">{{ $proposal->project_title }}</td>
                        <td class="px-4 py-3 text-slate-600">{{ $proposal->category?->name ?? '--' }}</td>
                        <td class="px-4 py-3">
                            <span class="badge badge-{{ $proposal->status }}">{{ $proposal->status }}</span>
                        </td>
                        <td class="px-4 py-3 text-slate-600">{{ number_format($proposal->est_budget) }}</td>
                        <td class="px-4 py-3">
                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ route('proposals.show', $proposal) }}" class="btn btn-secondary">View</a>
                                @hasanyrole('Proponent|Admin')
                                    <a href="{{ route('proposals.edit', $proposal) }}" class="btn btn-secondary">Edit</a>
                                    <form method="POST" action="{{ route('proposals.destroy', $proposal) }}"
                                        onsubmit="return confirm('Delete this proposal?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger">Delete</button>
                                    </form>
                                @endhasanyrole
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-4 py-10 text-center text-slate-500">No proposals yet.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $proposals->links() }}
    </div>
</x-layout>
