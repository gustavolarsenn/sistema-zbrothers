<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductPicking extends Model
{
    use HasFactory;

    public const DATE_COLUMN = 'dataCriacao';
    public const STATUS_PENDING = 'Pendente';
    public const STATUS_SEPARATING = 'Separando';
    public const STATUS_PACKED = 'Embalada';
    public const STATUS_CANCELED = 'Cancelada';

    protected $table = 'product_picking';

    public $timestamps = false;

    protected $fillable = [
        'picking_operator_id',
        'idOrigem',
        'objOrigem',
        'situacao',
        'situacaoCheckout',
        'dataCriacao',
        'itens',
        'qtdVolumes',
        'numero',
        'dataEmissao',
        'numeroPedidoEcommerce',
        'idFormaEnvio',
        'formaEnvio',
        'idContato',
        'destinatario',
        'situacaoOrigem',
        'dataSeparacao',
        'dataCheckout',
        'idOrigemVinc',
        'objOrigemVinc',
        'situacaoVenda',
    ];

    protected function casts(): array
    {
        return [
            'dataCriacao' => 'date',
            'dataEmissao' => 'date',
            'dataSeparacao' => 'date',
            'dataCheckout' => 'date',
            'itens' => 'array',
            'qtdVolumes' => 'integer',
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

    public function scopePacked($query)
    {
        return $query->where('situacao', self::STATUS_PACKED);
    }
}
