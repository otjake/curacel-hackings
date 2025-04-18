<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Enums\PaymentStatus;
use App\Services\WorkflowService;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens;
    use HasFactory;
    use HasProfilePhoto;
    use HasRoles;
    use Notifiable;
    use SoftDeletes;
    use TwoFactorAuthenticatable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'fusionauth_id',
        'fusionauth_access_token',
        'fusionauth_refresh_token',
        'payer_id',
        'last_login_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'last_login_at' => 'datetime',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array<int, string>
     */
    protected $appends = [
        'profile_photo_url',
    ];

    protected $guard_name = 'admin';

    public function payer(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Payer::class);
    }

    public function approvalLevels(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(ApprovalLevel::class, 'approval_level_users');
    }

    /**
     * Assert that the user can review a particular payment.
     *
     * @param BulkPayout|Payment $payment
     *
     * @return bool
     */
    public function canReviewPayment(Payment|BulkPayout $payment): bool
    {
        if (! $this->payer->is($payment->payer)) {
            return false;
        }

        if (! in_array($payment->status, [PaymentStatus::AWAITING_APPROVAL, PaymentStatus::FAILED])) {
            return false;
        }

        $currentApprovalLevel = WorkflowService::getCurrentWorkflowLevelFor($payment);

        if (! $currentApprovalLevel) {
            return true;
        }

        return $currentApprovalLevel->users()->where('user_id', $this->id)->exists();
    }

    public function activities()
    {
        return $this->morphMany('Spatie\Activitylog\Models\Activity', 'causer');
    }

    public function otps()
    {
        return $this->hasMany(Otp::class);
    }
}
