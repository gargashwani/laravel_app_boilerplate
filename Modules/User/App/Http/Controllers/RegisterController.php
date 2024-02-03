<?php

namespace Modules\User\App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Modules\Superadmin\App\Models\Tenant;

class RegisterController extends Controller
{
    public function register(Request $request): JsonResponse
    {
        DB::beginTransaction();
        try {
            $payload = $request->all();

            // check if tenant email or phone already exists.
            $tenant = Tenant::where('owner_email', $payload['email'])->first();
            if ($tenant) {
                return response()->json(['error' => 'Tenant with this email already exists.'], 422);
            }


            // if tenant email already exists, then return a json response with error message, that this email already exists as a tenant.
            $tenant = Tenant::create([
                'owner_name' => $payload['name'],
                'owner_email' => $payload['email']
            ]);
            $tenant->save();

            // check if user with the same email or phone already exists.
            $user = User::where('email', $payload['email'])->first();
            if ($user) {
                return response()->json(['error' => 'User with this email already exists.'], 422);
            }

            // if user with the same email or phone already exists, then return a json response with error message, that this email already exists as a user.
            $user = User::where('phone', $payload['phone'])->first();
            if ($user) {
                return response()->json(['error' => 'User with this phone already exists.'], 422);
            }

            $user = new User();
            $user->id = Str::uuid();
            $user->name = $payload['name'];
            $user->email = $payload['email'];
            $user->phone = $payload['phone'] ?? '';
            $user->visitor = $_SERVER['REMOTE_ADDR'] ?? '';
            $user->password =  bcrypt($payload['password']);  // Use your password hashing method
            $user->tenant_id =  $tenant->id;
            $user->save();

            // if tenant email already exists, then return a json response with error message, that this email already exists as a tenant.
            if (!$tenant) {
                return response()->json(['error' => 'Tenant with this email already exists.'], 422);
            }

            // if tenant email does not exist, then create a new tenant and return a json response with success message, that the tenant was created successfully.
            DB::commit();
            return response()->json(['message' => 'Tenant created successfully.']);
        } catch (\Exception $e) {
            DB::rollBack();
            dd($e);
        }
    }
}
