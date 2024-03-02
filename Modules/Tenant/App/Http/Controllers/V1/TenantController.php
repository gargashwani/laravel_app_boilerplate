<?php

namespace Modules\Tenant\App\Http\Controllers\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;

class TenantController extends Controller
{
    /**
     * Display a listing of the resource.
     * @param Request $request
     */
    public function create_tenant(Request $request)
    {
        try {
            // Create Tenant with all the required data.
            $token = JWTAuth::getToken();
            $user = JWTAuth::toUser($token);
            $userId = $user->id;

            // Check, if current user is already connected with any organization.
            // And if user is connected, then return error
            $checkIfAlreadyHaveDB = DB::connection('superadmin')->table('tenant_db_configs')
            ->where('tenant_id',$userId)
            ->first();

            if($checkIfAlreadyHaveDB != null){
                return response()->json([
                    'message'=>'You are already a Tenant, please connect with Support Team.'
                ]);
            }

            $availableTenantDB = DB::connection('superadmin')->table('tenant_db_configs')
            ->whereNull('tenant_id')
            ->first();

            $availableTenantDBID = DB::connection('superadmin')->table('tenant_db_configs')
            ->where('id',$availableTenantDB->id)
            ->update([
                'tenant_id'=>$userId
            ]);

            // Return Tenant ID
            return response()->json([
                'status' => true,
            ]);
        } catch (\Exception $e) {
            return response()->json($e);
        }
    }

}
