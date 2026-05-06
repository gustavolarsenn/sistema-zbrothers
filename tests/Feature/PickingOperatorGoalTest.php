<?php

namespace Tests\Feature;

use App\Models\PickingOperator;
use App\Models\PickingOperatorGoal;
use App\Models\ProductPicking;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PickingOperatorGoalTest extends TestCase
{
    use RefreshDatabase;

    public function test_goal_counts_only_packed_pickings_from_same_operator_and_date(): void
    {
        $operator = PickingOperator::create(['name' => 'Ana Souza']);
        $otherOperator = PickingOperator::create(['name' => 'Bruno Lima']);

        $goal = PickingOperatorGoal::create([
            'picking_operator_id' => $operator->id,
            'date' => '2026-04-27',
            'goal' => 3,
            'time_goal' => '08:00',
        ]);

        ProductPicking::create([
            'picking_operator_id' => $operator->id,
            'situacao' => ProductPicking::STATUS_PACKED,
            'dataCriacao' => '2026-04-27',
            'qtdVolumes' => 1,
        ]);

        ProductPicking::create([
            'picking_operator_id' => $operator->id,
            'situacao' => ProductPicking::STATUS_PENDING,
            'dataCriacao' => '2026-04-27',
            'qtdVolumes' => 1,
        ]);

        ProductPicking::create([
            'picking_operator_id' => $otherOperator->id,
            'situacao' => ProductPicking::STATUS_PACKED,
            'dataCriacao' => '2026-04-27',
            'qtdVolumes' => 1,
        ]);

        ProductPicking::create([
            'picking_operator_id' => $operator->id,
            'situacao' => ProductPicking::STATUS_PACKED,
            'dataCriacao' => '2026-04-28',
            'qtdVolumes' => 1,
        ]);

        $this->assertSame(1, $goal->fresh()->packed_count);
        $this->assertSame(33, $goal->fresh()->progress_percentage);
    }
}
