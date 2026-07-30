<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;

class AndroidAppVersion extends Model
{
    use HasUlids;
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
