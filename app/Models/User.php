<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Http\Request;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'role_id',
        'full_name',
        'email',
        'status',
        'registered_at',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'password' => 'hashed',
    ];

    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class, 'role_id', 'id');
    }

    public function shops(): HasMany
    {
        return $this->hasMany(Shop::class,'merchant_id', 'id');
    }

    public function scopeFilter(Builder $builder, Request $request)
    {
        return $builder->when($request->search, function (Builder $query, string $search) {
            $query->where('full_name', 'like', '%'.$search.'%')->orWhere('email', 'like','%'.$search.'%');
        })->when($request->status, function(Builder $query, string $status) {
            $query->where('status', '=', $status);
        })->when($request->registered_at, function (Builder $query, $registered_at) {
            $query->whereDate('registered_at', $registered_at);
        })->when($request->withShops, function (Builder $query) {
            $query->with('shops');
        });
    }
}
