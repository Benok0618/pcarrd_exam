<x-layout :title="$proposal->project_title">
    <div class="mx-auto max-w-3xl space-y-6">
        <div class="flex items-start justify-between">
            <div>
                <a href="{{ route('proposals.index') }}" class="text-sm text-slate-500 hover:text-slate-800">&larr; Back to proposals</a>
                <h1 class="mt-1 text-xl font-semibold text-slate-900">{{ $proposal->project_title }}</h1>
                <p class="mt-1 text-sm text-slate-500">
                    Submitted by {{ $proposal->creator?->name ?? '--' }}
                </p>
            </div>
            <span class="badge badge-{{ $proposal->status }}">{{ $proposal->status }}</span>
        </div>

        <div class="card p-6">
            <dl class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                <div>
                    <dt class="text-xs uppercase tracking-wide text-slate-400">Category</dt>
                    <dd class="mt-1 text-sm text-slate-800">{{ $proposal->category?->name ?? '--' }}</dd>
                </div>
                <div>
                    <dt class="text-xs uppercase tracking-wide text-slate-400">Project lead</dt>
                    <dd class="mt-1 text-sm text-slate-800">{{ $proposal->projectLead?->name ?? '--' }}</dd>
                </div>
                <div>
                    <dt class="text-xs uppercase tracking-wide text-slate-400">Start date</dt>
                    <dd class="mt-1 text-sm text-slate-800">{{ $proposal->start_date?->format('M j, Y') }}</dd>
                </div>
                <div>
                    <dt class="text-xs uppercase tracking-wide text-slate-400">End date</dt>
                    <dd class="mt-1 text-sm text-slate-800">{{ $proposal->end_date?->format('M j, Y') }}</dd>
                </div>
                <div>
                    <dt class="text-xs uppercase tracking-wide text-slate-400">Estimated budget</dt>
                    <dd class="mt-1 text-sm text-slate-800">{{ number_format($proposal->est_budget) }}</dd>
                </div>
                <div>
                    <dt class="text-xs uppercase tracking-wide text-slate-400">Attachments</dt>
                    <dd class="mt-1 text-sm">
                        @forelse($proposal->attachment ?? [] as $file)
                            <a href="{{ asset('storage/' . $file) }}" target="_blank" class="block text-slate-700 underline hover:text-slate-900">
                                {{ basename($file) }}
                            </a>
                        @empty
                            <span class="text-slate-500">--</span>
                        @endforelse
                    </dd>
                </div>
            </dl>

            <div class="mt-6 border-t border-slate-100 pt-4">
                <dt class="text-xs uppercase tracking-wide text-slate-400">Description</dt>
                <dd class="mt-1 text-sm text-slate-700">{{ $proposal->description ?: '--' }}</dd>
            </div>
            <div class="mt-4">
                <dt class="text-xs uppercase tracking-wide text-slate-400">Objective</dt>
                <dd class="mt-1 text-sm text-slate-700">{{ $proposal->objective }}</dd>
            </div>
        </div>

        @role('Reviewer|Admin')
            <div class="card p-6">
                <h2 class="text-sm font-semibold text-slate-900">Review</h2>
                <form method="POST" action="{{ route('proposals.status', $proposal) }}" class="mt-4 space-y-4">
                    @csrf
                    @method('PATCH')
                    <div>
                        <label for="status" class="label">Status</label>
                        <select id="status" name="status" class="input" required>
                            @foreach(['new' => 'New', 'approved' => 'Approved', 'disapproved' => 'Disapproved'] as $value => $label)
                                <option value="{{ $value }}" @selected($proposal->status === $value)>{{ $label }}</option>
                            @endforeach
                        </select>
                        @error('status') <p class="error">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="remarks" class="label">
                            Remarks
                            <span id="remarks-required" class="ml-1 text-xs font-normal text-rose-600 @if(old('status', $proposal->status) !== 'disapproved') hidden @endif">Required when disapproving</span>
                        </label>
                        <textarea id="remarks" name="remarks" rows="3" class="input" placeholder="Add remarks about the attachment" @if(old('status', $proposal->status) === 'disapproved') required @endif>{{ old('remarks') }}</textarea>
                        @error('remarks') <p class="error">{{ $message }}</p> @enderror
                    </div>
                    <button type="submit" class="btn btn-primary">Save review</button>
                </form>

                <script>
                    (function () {
                        const status = document.getElementById('status');
                        const remarks = document.getElementById('remarks');
                        const hint = document.getElementById('remarks-required');
                        if (!status || !remarks) return;
                        const sync = () => {
                            const required = status.value === 'disapproved';
                            remarks.required = required;
                            hint?.classList.toggle('hidden', !required);
                        };
                        status.addEventListener('change', sync);
                        sync();
                    })();
                </script>
            </div>
        @endrole

        @role('Proponent')
            <div class="flex items-center gap-3">
                <a href="{{ route('proposals.edit', $proposal) }}" class="btn btn-primary">Edit proposal</a>
            </div>
        @endrole

        <div class="card p-6">
            <h2 class="text-sm font-semibold text-slate-900">Activity</h2>
            <ol class="mt-4 space-y-4">
                @forelse($proposal->timelines as $timeline)
                    <li class="border-l-2 border-slate-200 pl-4">
                        <p class="text-sm text-slate-800">{{ $timeline->activity }}</p>
                        <p class="text-xs text-slate-500">
                            {{ $timeline->user?->name ?? 'System' }} &middot; {{ $timeline->created_at->format('M j, Y g:i A') }}
                        </p>
                        @if($timeline->remarks)
                            <p class="mt-1 rounded bg-slate-50 px-3 py-2 text-sm text-slate-600">{{ $timeline->remarks }}</p>
                        @endif
                    </li>
                @empty
                    <li class="text-sm text-slate-500">No activity recorded.</li>
                @endforelse
            </ol>
        </div>
    </div>
</x-layout>
