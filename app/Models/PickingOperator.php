<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PickingOperator extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'registration',
        'active',
    ];

    protected function casts(): array
    {
        return [
            'active' => 'boolean',
        ];
    }

    public function productPickings(): HasMany
    {
        return $this->hasMany(ProductPicking::class);
    }

    public function goals(): HasMany
    {
        return $this->hasMany(PickingOperatorGoal::class);
    }
}
