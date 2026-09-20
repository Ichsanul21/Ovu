<?php

namespace Tests\Unit;

use App\Services\CycleTipsService;
use Tests\TestCase;

class CycleTipsServiceTest extends TestCase
{
    public function test_tip_for_each_phase_has_content(): void
    {
        $service = app(CycleTipsService::class);

        foreach ([2, 8, 14, 23] as $day) {
            $tip = $service->forDay($day, 'kesehatan');
            $this->assertNotEmpty($tip['kondisi']);
            $this->assertNotEmpty($tip['tips']);
        }
    }

    public function test_kb_tip_is_explicit_on_fertile_days(): void
    {
        $tip = app(CycleTipsService::class)->forDay(14, 'kb');
        $joined = implode(' ', $tip['tips']);

        $this->assertStringContainsString('proteksi', $joined);
    }

    public function test_no_emdash_in_any_tip(): void
    {
        $service = app(CycleTipsService::class);

        for ($day = 1; $day <= 35; $day++) {
            $tip = $service->forDay($day, 'promil');
            $text = $tip['kondisi'].' '.implode(' ', $tip['tips']);
            $this->assertStringNotContainsString('—', $text, "Emdash ditemukan di hari {$day}");
        }
    }
}
