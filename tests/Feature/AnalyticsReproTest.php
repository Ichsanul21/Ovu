<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AnalyticsReproTest extends TestCase
{
    use RefreshDatabase;

    public function test_analytics_renders_with_cycles_and_tricky_logs(): void
    {
        $user = User::factory()->create(['role' => 'wife']);
        $user->profile()->create(['full_name' => 'Tes', 'typical_cycle_length' => 28]);
        foreach (['2026-05-04', '2026-06-01', '2026-06-29', '2026-07-27'] as $s) {
            $user->cycles()->create(['start_date' => $s, 'end_date' => $s]);
        }
        $user->dailyLogs()->create([
            'log_date' => now()->subDays(2)->toDateString(),
            'bleeding' => 2, 'cramp' => null, 'energy' => null,
            'mood' => 'sensitif', 'diary' => 'Kata "kutip" dan <tag> plus baris baru.',
        ]);
        $user->dailyLogs()->create([
            'log_date' => now()->subDays(1)->toDateString(),
            'bleeding' => 0, 'cramp' => 5, 'energy' => 1,
        ]);

        $html = $this->actingAs($user)->get(route('analytics'))->assertOk()->getContent();

        preg_match('#<script type="application/json" id="ovu-chart-data">(.*?)</script>#s', $html, $m);
        $this->assertNotEmpty($m, 'Blok JSON chart harus ada.');

        $data = json_decode($m[1], true);
        $this->assertIsArray($data);
        $this->assertCount(3, $data['cycleValues']);
        foreach ($data['cycleValues'] as $v) {
            $this->assertIsInt($v);
        }
        $this->assertCount(2, $data['logLabels']);
        $this->assertSame([null, 1], $data['logEnergy']);
        $this->assertSame([null, 5], $data['logCramp']);
    }

    public function test_analytics_renders_empty_state(): void
    {
        $user = User::factory()->create(['role' => 'wife']);
        $user->profile()->create(['full_name' => 'Tes']);

        $html = $this->actingAs($user)->get(route('analytics'))->assertOk()->getContent();

        $this->assertStringContainsString('Belum ada data', $html);
        $this->assertStringContainsString('Belum ada catatan', $html);
    }
}
