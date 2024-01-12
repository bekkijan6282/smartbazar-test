<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;

    const ROLES = [
        ['name' => 'superadmin', 'display_name' => 'SuperAdmin'],
        ['name' => 'admin', 'display_name' => 'Admin'],
        ['name' => 'merchant', 'display_name' => 'Merchant'],
    ];

    protected $fillable = [
        'name',
        'display_name',
    ];
}
