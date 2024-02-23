<?php

namespace Modules\Tenant\App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class TenantController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function create_tenant()
    {
        // Create Tenant with all the required data.
        // Return Tenant ID
        return response()->json([
            'status' => true,
        ]);
    }

}
