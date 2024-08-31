<?php

namespace Wovosoft\LaravelCashier\Models;

use App\Models\Slip;
use App\Models\User;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Wovosoft\LaravelCashier\Enums\PaymentStatus;

/**
 * @property int $id
 * @property int|null $created_by_id
 * @property int|null $slip_id
 * @property float|null $payment_amount
 * @property float|null $admin_fee
 * @property float|null $agent_fee
 * @property PaymentStatus $status
 * @property \Illuminate\Support\Carbon|null $completed_at
 * @property \Illuminate\Support\Carbon|null $failed_at
 * @property \Illuminate\Support\Carbon|null $returned_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read User|null $createdBy
 * @property-read bool $is_completed
 * @property-read bool $is_failed
 * @property-read bool $is_returned
 * @property-read Slip|null $slip
 *
 * @method static \Illuminate\Database\Eloquent\Builder|Payment newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Payment newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Payment query()
 * @method static \Illuminate\Database\Eloquent\Builder|Payment whereAdminFee($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Payment whereAgentFee($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Payment whereCompletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Payment whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Payment whereCreatedById($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Payment whereFailedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Payment whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Payment wherePaymentAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Payment whereReturnedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Payment whereSlipId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Payment whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Payment whereUpdatedAt($value)
 *
 * @mixin \Eloquent
 */
class Payment extends Model
{
    use HasFactory;

    protected function casts(): array
    {
        return [
            'payment_amount' => 'float',
            'admin_fee' => 'float',
            'agent_fee' => 'float',
            'status' => PaymentStatus::class,
            'completed_at' => 'datetime',
            'failed_at' => 'datetime',
            'returned_at' => 'datetime',
        ];
    }

    public function slip(): BelongsTo
    {
        return $this->belongsTo(Slip::class, 'slip_id');
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by_id');
    }

    public function isCompleted(): Attribute
    {
        return Attribute::get(fn (): bool => ! is_null($this->completed_at));
    }

    public function isFailed(): Attribute
    {
        return Attribute::get(fn (): bool => ! is_null($this->failed_at));
    }

    public function isReturned(): Attribute
    {
        return Attribute::get(fn (): bool => ! is_null($this->returned_at));
    }
}
