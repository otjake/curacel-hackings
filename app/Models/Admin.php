<?php

namespace App\Models;

use App\Enums\AdminStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Traits\HasRoles;

class Admin extends Authenticatable
{
    use HasFactory;
    use HasRoles;
    use Notifiable;

    protected $guarded = ['id'];

    protected $hidden = ['password'];

    // protected $casts = [
    //     'status' => AdminStatus::class,
    // ];

    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class);
    }
}
