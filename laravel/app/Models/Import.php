<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Import extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'product_id',
        'quantity',
        'cost_price_usd',
        'exchange_rate',
        'extra_fees_brl',
        'total_cost_brl',
        'store_name',
        'import_date',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */

    protected function casts(): array
    {
        return [
            'cost_price_usd' => 'decimal:2',
            'exchange_rate' => 'decimal:2',
            'extra_fees_brl' => 'decimal:2',
            'total_cost_brl' => 'decimal:2',
            'import_date' => 'date',
        ];
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}