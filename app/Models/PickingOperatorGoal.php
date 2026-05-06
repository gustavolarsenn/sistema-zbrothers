<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PickingOperatorGoal extends Model
{
    use HasFactory;

    protected $fillable = [
        'picking_operator_id',
        'date',
        'goal',
        'time_goal',
    ];

    protected $appends = [
        'packed_count',
        'progress_percentage',
    ];

    protected function casts(): array
    {
        return [
            'date' => 'date',
            'goal' => 'integer',
        ];
    }

    public function operator(): BelongsTo
    {
        return $this->belongsTo(PickingOperator::class, 'picking_operator_id');
    }

    protected function packedCount(): Attribute
    {
        return Attribute::get(fn (): int => ProductPicking::query()
            ->where('picking_operator_id', $this->picking_operator_id)
            ->whereDate(ProductPicking::DATE_COLUMN, $this->date)
            ->where('situacao', ProductPicking::STATUS_PACKED)
            ->count());
    }

    protected function progressPercentage(): Attribute
    {
        return Attribute::get(function (): int {
            if ($this->goal < 1) {
                return 0;
            }

            return min(100, (int) round(($this->packed_count / $this->goal) * 100));
        });
    }
}
