<?php

namespace Modules\Superadmin\App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class Tenant extends Model
{
    use HasUuids;
    protected $table = 'tenants';
    protected $guarded = [];
}
