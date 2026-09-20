<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OvuRemakeFlowTest extends TestCase
{
    use RefreshDatabase;

    private function wife(): User
    {
        $user = User::factory()->create(['role' => 'wife']);
        $user->profile()->create(['full_name' => 'Tes', 'typical_cycle_length' => 28]);
        $user->cycles()->create(['start_date' => now()->subDays(10)->toDateString(), 'end_date' => now()->subDays(6)->toDateString()]);

        return $user;
    }

    public function test_all_main_pages_render(): void
    {
        $user = $this->wife();

        foreach (['dashboard', 'calendar', 'logs.index', 'cycles.index', 'analytics', 'projection', 'report', 'pregnancy.index', 'partner', 'settings', 'push.settings', 'profile.edit', 'onboarding.show'] as $route) {
            $response = $this->actingAs($user)->get(route($route));
            $response->assertOk();
        }
    }

    public function test_log_sheet_stores_catalog_data(): void
    {
        $user = $this->wife();

        $response = $this->actingAs($user)->post(route('logs.store'), [
            'log_date' => now()->toDateString(),
            'col_cramp' => 4,
            'col_mood' => 'sensitif',
            'sym_weak' => 1,
            'sym_bloating' => 1,
            'diary' => 'Hari yang berat.',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('daily_logs', ['user_id' => $user->id, 'cramp' => 4, 'mood' => 'sensitif']);
    }

    public function test_pill_toggle_and_pin_flow(): void
    {
        $user = $this->wife();

        $this->actingAs($user)->post(route('pill.toggle'))->assertRedirect();
        $this->assertDatabaseHas('daily_logs', ['user_id' => $user->id, 'pill_taken' => 1]);

        $this->actingAs($user)->post(route('settings.pin'), [
            'pin' => '1234',
            'current_password' => 'password',
        ])->assertRedirect();

        $this->actingAs($user)->get(route('dashboard'))->assertRedirect(route('pin.show'));

        $this->actingAs($user)->post(route('pin.unlock'), ['pin' => '1234'])->assertRedirect();
        $this->actingAs($user)->get(route('dashboard'))->assertOk();
    }
}
