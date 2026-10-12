<x-layout title="Edit proposal">
    <div class="mx-auto max-w-2xl">
        <h1 class="mb-1 text-xl font-semibold text-slate-900">Edit proposal</h1>
        <p class="mb-6 text-sm text-slate-500">{{ $proposal->project_title }}</p>

        <form method="POST" action="{{ route('proposals.update', $proposal) }}" enctype="multipart/form-data" class="card space-y-5 p-6">
            @csrf
            @method('PUT')

            <div>
                <label for="project_title" class="label">Project title</label>
                <input id="project_title" type="text" name="project_title" value="{{ old('project_title', $proposal->project_title) }}" class="input" required>
                @error('project_title') <p class="error">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="category_id" class="label">Category</label>
                <select id="category_id" name="category_id" class="input" required>
                    <option value="">Select a category</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" @selected(old('category_id', $proposal->category_id) == $category->id)>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
                @error('category_id') <p class="error">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="project_lead" class="label">Project lead</label>
                <select id="project_lead" name="project_lead" class="input" required>
                    <option value="">Select a project lead</option>
                    @foreach($users as $user)
                        <option value="{{ $user->id }}" @selected(old('project_lead', $proposal->project_lead) == $user->id)>
                            {{ $user->name }}
                        </option>
                    @endforeach
                </select>
                @error('project_lead') <p class="error">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="description" class="label">Description</label>
                <textarea id="description" name="description" rows="3" class="input">{{ old('description', $proposal->description) }}</textarea>
                @error('description') <p class="error">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="objective" class="label">Objective</label>
                <textarea id="objective" name="objective" rows="3" class="input" required>{{ old('objective', $proposal->objective) }}</textarea>
                @error('objective') <p class="error">{{ $message }}</p> @enderror
            </div>

            <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
                <div>
                    <label for="start_date" class="label">Start date</label>
                    <input id="start_date" type="date" name="start_date" value="{{ old('start_date', $proposal->start_date?->format('Y-m-d')) }}" class="input" required>
                    @error('start_date') <p class="error">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label for="end_date" class="label">End date</label>
                    <input id="end_date" type="date" name="end_date" value="{{ old('end_date', $proposal->end_date?->format('Y-m-d')) }}" class="input" required>
                    @error('end_date') <p class="error">{{ $message }}</p> @enderror
                </div>
            </div>

            <div>
                <label for="est_budget" class="label">Estimated budget</label>
                <input id="est_budget" type="number" name="est_budget" value="{{ old('est_budget', $proposal->est_budget) }}" min="0" step="1" class="input" required>
                @error('est_budget') <p class="error">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="attachments" class="label">Add attachments (PDF, DOC, DOCX &middot; max 10MB each &middot; leave empty to keep current)</label>
                <input id="attachments" type="file" name="attachments[]" class="input" multiple>
                @if($proposal->attachment)
                    <ul class="mt-1 space-y-0.5 text-xs text-slate-500">
                        @foreach($proposal->attachment as $file)
                            <li>Current: {{ basename($file) }}</li>
                        @endforeach
                    </ul>
                @endif
                @error('attachments') <p class="error">{{ $message }}</p> @enderror
                @error('attachments.*') <p class="error">{{ $message }}</p> @enderror
            </div>

            <div class="flex items-center gap-3 pt-2">
                <button type="submit" class="btn btn-primary">Update proposal</button>
                <a href="{{ route('proposals.show', $proposal) }}" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</x-layout>
