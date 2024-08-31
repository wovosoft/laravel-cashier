<?php

namespace Wovosoft\LaravelCashier\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $payment_id
 * @property int|null $owner_id
 * @property string $type
 * @property string $amount
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 *
 * @method static Builder|Income adminIncome()
 * @method static Builder|Income agentIncome()
 * @method static Builder|Income newModelQuery()
 * @method static Builder|Income newQuery()
 * @method static Builder|Income query()
 * @method static Builder|Income whereAmount($value)
 * @method static Builder|Income whereCreatedAt($value)
 * @method static Builder|Income whereId($value)
 * @method static Builder|Income whereOwnerId($value)
 * @method static Builder|Income wherePaymentId($value)
 * @method static Builder|Income whereType($value)
 * @method static Builder|Income whereUpdatedAt($value)
 *
 * @mixin \Eloquent
 */
class Income extends Model
{
    use HasFactory;

    public function scopeAdminIncome(Builder $builder): Builder
    {
        return $builder->where('type', 'admin');
    }

    public function scopeAgentIncome(Builder $builder): Builder
    {
        return $builder->where('type', 'agent');
    }
}
