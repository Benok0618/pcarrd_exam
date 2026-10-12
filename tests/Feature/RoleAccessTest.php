<?php

namespace Tests\Feature;

use App\Models\Proposal;
use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RoleAccessTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(DatabaseSeeder::class);
    }

    public function test_guests_are_redirected_to_login(): void
    {
        $this->get(route('proposals.index'))->assertRedirect(route('login'));
    }

    public function test_admin_can_log_in_with_username_and_password(): void
    {
        $this->post(route('login'), ['email' => 'admin', 'password' => 'admin'])
            ->assertRedirect(route('proposals.index'));

        $this->assertAuthenticated();
    }

    public function test_proponent_can_access_create_forms(): void
    {
        $proponent = User::where('email', 'proponent@example.com')->first();

        $this->actingAs($proponent)->get(route('proposals.create'))->assertOk();
        $this->actingAs($proponent)->get(route('categories.create'))->assertOk();
    }

    public function test_reviewer_cannot_access_create_forms(): void
    {
        $reviewer = User::where('email', 'reviewer@example.com')->first();

        $this->actingAs($reviewer)->get(route('proposals.create'))->assertForbidden();
        $this->actingAs($reviewer)->get(route('categories.create'))->assertForbidden();
    }

    public function test_reviewer_can_update_proposal_status(): void
    {
        $reviewer = User::where('email', 'reviewer@example.com')->first();
        $proposal = Proposal::first();

        $this->actingAs($reviewer)
            ->patch(route('proposals.status', $proposal), ['status' => 'approved', 'remarks' => 'Looks good.'])
            ->assertRedirect(route('proposals.show', $proposal));

        $this->assertDatabaseHas('proposals', ['id' => $proposal->id, 'status' => 'approved']);
        $this->assertDatabaseHas('timelines', ['project_id' => $proposal->id, 'remarks' => 'Looks good.']);
    }
}
