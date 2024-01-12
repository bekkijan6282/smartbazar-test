<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class Shop extends Model
{
    use HasFactory;

    protected $fillable = [
        'merchant_id',
        'address',
        'schedule',
        'latitude',
        'longitude',
    ];

    public function scopeFilter(Builder $builder, Request $request)
    {
        return $builder->when($request->merchant_id, function (Builder $query, int $merchant_id) {
            $query->where('merchant_id', $merchant_id);
        })->when($request->latitude, function (Builder $query, float $lat) {
            $query->where('latitude', $lat);
        })->when($request->longitude, function (Builder $query, float $long) {
            $query->where('longitude', '$long');
        })->when($request->schedule, function (Builder $query, string $schedule) {
            $query->where('schedule', $schedule);
        })->when($request->address, function (Builder $query, string $address) {
            $query->where('address', $address);
        });
    }
}
