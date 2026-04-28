<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductPicking extends Model
{
    use HasFactory;

    public const STATUS_PENDING = 'Pendente';
    public const STATUS_SEPARATING = 'Separando';
    public const STATUS_PACKED = 'Embalada';
    public const STATUS_CANCELED = 'Cancelada';

    protected $table = 'product_picking';

    protected $fillable = [
        'picking_operator_id',
        'order_number',
        'product_code',
        'product_name',
        'quantity',
        'status',
        'picking_date',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'picking_date' => 'date',
            'quantity' => 'integer',
        ];
    }

    public static function statuses(): array
    {
        return [
            self::STATUS_PENDING,
            self::STATUS_SEPARATING,
            self::STATUS_PACKED,
            self::STATUS_CANCELED,
        ];
    }

    public function operator(): BelongsTo
    {
        return $this->belongsTo(PickingOperator::class, 'picking_operator_id');
    }
}
