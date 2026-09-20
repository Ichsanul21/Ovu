<?php

namespace Tests\Unit;

use App\Models\User;
use App\Services\CyclePredictor;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CyclePredictorTest extends TestCase
{
    use RefreshDatabase;

    public function test_predicts_from_regular_history(): void
    {
        $user = User::factory()->create(['role' => 'wife']);
        foreach (['2026-05-04', '2026-06-01', '2026-06-29', '2026-07-27'] as $start) {
            $user->cycles()->create(['start_date' => $start, 'end_date' => $start]);
        }

        $result = app(CyclePredictor::class)->predict($user, Carbon::parse('2026-08-10'));

        $this->assertEquals('2026-08-24', $result['next_period']->toDateString());
        $this->assertEquals('2026-08-10', $result['ovulation_date']->toDateString());
        $this->assertEquals('Tinggi', $result['confidence']);
        $this->assertEquals(15, $result['cycle_day']);
    }

    public function test_empty_history_returns_nulls(): void
    {
        $user = User::factory()->create(['role' => 'wife']);

        $result = app(CyclePredictor::class)->predict($user);

        $this->assertNull($result['next_period']);
        $this->assertSame('Rendah', $result['confidence']);
    }

    public function test_phase_mapping(): void
    {
        $p = app(CyclePredictor::class);

        $this->assertSame('menstruasi', $p->phase(3));
        $this->assertSame('folikuler', $p->phase(9));
        $this->assertSame('ovulasi', $p->phase(14));
        $this->assertSame('luteal', $p->phase(22));
    }
}
