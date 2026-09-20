<?php

namespace Tests\Unit;

use App\Models\User;
use App\Services\AssistantService;
use App\Services\CyclePredictor;
use App\Services\CycleTipsService;
use App\Services\PregnancyService;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OvuRemakeTest extends TestCase
{
    use RefreshDatabase;

    private function wife(array $profile = []): User
    {
        $user = User::factory()->create(['role' => 'wife']);
        $user->profile()->create(array_merge(['full_name' => 'Tes'], $profile));

        return $user->fresh();
    }

    public function test_single_cycle_uses_typical_length(): void
    {
        $user = $this->wife(['typical_cycle_length' => 32]);
        $user->cycles()->create(['start_date' => '2026-08-01', 'end_date' => '2026-08-05']);

        $result = app(CyclePredictor::class)->predict($user, Carbon::parse('2026-08-10'));

        $this->assertEquals('2026-09-02', $result['next_period']->toDateString());
        $this->assertEquals('2026-08-19', $result['ovulation_date']->toDateString());
        $this->assertSame('Rendah', $result['confidence']);
        $this->assertEquals(10, $result['cycle_day']);
    }

    public function test_projection_rolls_with_real_data(): void
    {
        $user = $this->wife(['typical_cycle_length' => 28]);
        $user->cycles()->create(['start_date' => '2026-08-01', 'end_date' => '2026-08-05']);

        $p = app(CyclePredictor::class);
        $before = $p->project($user, 5);
        $this->assertNotEmpty($before);
        $firstBefore = $before[0]['start']->toDateString();

        $user->cycles()->create(['start_date' => '2026-08-30', 'end_date' => '2026-09-03']);
        $after = $p->project($user, 5);
        $firstAfter = $after[0]['start']->toDateString();

        $this->assertNotEquals($firstBefore, $firstAfter);
        $this->assertEquals('2026-09-28', $firstAfter);
    }

    public function test_projection_capped_at_five_years(): void
    {
        $user = $this->wife(['typical_cycle_length' => 28]);
        $user->cycles()->create(['start_date' => '2026-08-01', 'end_date' => '2026-08-05']);

        $projection = app(CyclePredictor::class)->project($user, 5);

        $this->assertNotEmpty($projection);
        $this->assertLessThanOrEqual(100, count($projection));
        $this->assertTrue(end($projection)['start']->lte(Carbon::parse('2026-08-01')->addYears(5)));
    }

    public function test_pregnancy_chance_and_countdown(): void
    {
        $p = app(CyclePredictor::class);
        $ovu = Carbon::parse('2026-08-19');
        $fs = Carbon::parse('2026-08-14');
        $fe = Carbon::parse('2026-08-20');

        $this->assertSame('Puncak', $p->pregnancyChance(Carbon::parse('2026-08-19'), $ovu, $fs, $fe)['label']);
        $this->assertSame('Tinggi', $p->pregnancyChance(Carbon::parse('2026-08-16'), $ovu, $fs, $fe)['label']);
        $this->assertSame('Rendah', $p->pregnancyChance(Carbon::parse('2026-08-25'), $ovu, $fs, $fe)['label']);

        $cd = $p->countdown(Carbon::parse('2026-08-10'), $ovu, Carbon::parse('2026-09-02'));
        $this->assertSame('ovulasi', $cd['target']);

        $late = $p->countdown(Carbon::parse('2026-09-10'), $ovu, Carbon::parse('2026-09-02'));
        $this->assertSame('telat', $late['target']);
    }

    public function test_catalog_has_seventy_items(): void
    {
        $count = 0;
        foreach (config('symptoms') as $cat) {
            $count += count($cat['items']);
        }

        $this->assertGreaterThanOrEqual(70, $count);
    }

    public function test_teen_tips_have_no_explicit_content(): void
    {
        $tip = app(CycleTipsService::class)->forDay(14, 'kb', true);
        $text = implode(' ', $tip['tips']);

        $this->assertStringNotContainsString('proteksi', $text);
        $this->assertStringContainsString('Tahukah kamu', $text);
    }

    public function test_assistant_welcomes_empty_user(): void
    {
        $user = $this->wife();
        $result = app(CyclePredictor::class)->predict($user);
        $cards = app(AssistantService::class)->cards($user, $result);

        $this->assertNotEmpty($cards);
        $this->assertSame('cycles.index', $cards[0]['action_route']);
    }

    public function test_pregnancy_status_math(): void
    {
        $user = $this->wife(['goal' => 'hamil', 'pregnancy_start' => '2026-06-01']);
        $status = app(PregnancyService::class)->status($user->fresh());

        $this->assertNotNull($status);
        $this->assertEquals('2027-03-08', $status['due']->toDateString());
        $this->assertContains($status['trimester'], [1, 2, 3]);
        $this->assertNotEmpty($status['checklist']);
    }
}
