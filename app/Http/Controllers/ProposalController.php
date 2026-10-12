<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Proposal;
use App\Models\Timeline;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProposalController extends Controller
{
    public function index()
    {
        $query = Proposal::with('category')->latest();

        if (auth()->user()->hasRole('Proponent')) {
            $query->where('created_by', auth()->id());
        }

        $proposals = $query->paginate(10);

        return view('proposal.index', compact('proposals'));
    }

    public function create()
    {
        return view('proposal.create', [
            'categories' => Category::orderBy('name')->get(),
            'users' => User::orderBy('name')->get(),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $this->validateProposal($request, true);
        $validated['attachment'] = $this->storeFiles($request);
        $validated['created_by'] = auth()->id();
        $validated['status'] = 'new';

        $proposal = Proposal::create($validated);

        Timeline::create([
            'project_id' => $proposal->id,
            'activity' => 'Proposal submitted',
            'updated_by' => auth()->id(),
            'updated_to' => 'new',
            'remarks' => null,
        ]);

        return redirect()->route('proposals.index')->with('success', 'Proposal created successfully.');
    }

    public function show(Proposal $proposal)
    {
        $proposal->load(['category', 'creator', 'projectLead', 'timelines.user']);

        return view('proposal.show', compact('proposal'));
    }

    public function edit(Proposal $proposal)
    {
        $this->authorizeOwner($proposal);

        return view('proposal.edit', [
            'proposal' => $proposal,
            'categories' => Category::orderBy('name')->get(),
            'users' => User::orderBy('name')->get(),
        ]);
    }

    public function update(Request $request, Proposal $proposal)
    {
        $this->authorizeOwner($proposal);

        $validated = $this->validateProposal($request, false);

        $filesChanged = $request->hasFile('attachments');

        if ($filesChanged) {
            $this->deleteFiles($proposal);
            $validated['attachment'] = $this->storeFiles($request);
        }

        $proposal->update($validated);

        Timeline::create([
            'project_id' => $proposal->id,
            'activity' => $filesChanged ? 'Attachments updated' : 'Proposal details updated',
            'updated_by' => auth()->id(),
            'updated_to' => $proposal->status,
            'remarks' => $filesChanged ? 'Attachments were changed.' : null,
        ]);

        return redirect()->route('proposals.index')->with('success', 'Proposal updated successfully.');
    }

    public function updateStatus(Request $request, Proposal $proposal)
    {
        $validated = $request->validate([
            'status' => 'required|in:new,approved,disapproved',
            'remarks' => 'required_if:status,disapproved|nullable|string',
        ]);

        $proposal->update(['status' => $validated['status']]);

        Timeline::create([
            'project_id' => $proposal->id,
            'activity' => 'Status updated to '.$validated['status'],
            'updated_by' => auth()->id(),
            'updated_to' => $validated['status'],
            'remarks' => $validated['remarks'] ?? null,
        ]);

        return redirect()->route('proposals.show', $proposal)->with('success', 'Proposal status updated.');
    }

    public function destroy(Proposal $proposal)
    {
        $this->authorizeOwner($proposal);

        $proposal->delete();

        return redirect()->route('proposals.index')->with('success', 'Proposal deleted successfully.');
    }

    public function restore($id)
    {
        $proposal = Proposal::withTrashed()->findOrFail($id);
        $this->authorizeOwner($proposal);
        $proposal->restore();

        return redirect()->route('proposals.index')->with('success', 'Proposal restored successfully.');
    }

    private function validateProposal(Request $request, bool $attachmentsRequired): array
    {
        $validated = $request->validate([
            'project_title' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'project_lead' => 'required|exists:users,id',
            'description' => 'nullable|string',
            'objective' => 'required|string',
            'attachments' => ($attachmentsRequired ? 'required' : 'nullable').'|array',
            'attachments.*' => 'file|mimes:pdf,doc,docx|max:10240',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'est_budget' => 'required|integer|min:0',
        ]);

        unset($validated['attachments']);

        return $validated;
    }
    private function storeFiles(Request $request): array
    {
        $paths = [];

        foreach ($request->file('attachments', []) as $file) {
            $paths[] = $file->store('attachments', 'public');
        }

        return $paths;
    }
    private function deleteFiles(Proposal $proposal): void
    {
        foreach ($proposal->attachment ?? [] as $path) {
            Storage::disk('public')->delete($path);
        }
    }
    private function authorizeOwner(Proposal $proposal): void
    {
        abort_unless(
            auth()->user()->hasAnyRole(['Reviewer', 'Admin']) || $proposal->created_by === auth()->id(),
            403
        );
    }
}
