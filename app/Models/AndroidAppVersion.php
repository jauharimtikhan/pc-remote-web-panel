<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AndroidAppVersion extends Model
{
    protected $fillable = [
        'version',
        'version_code',
        'bundle_url'
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];
}
