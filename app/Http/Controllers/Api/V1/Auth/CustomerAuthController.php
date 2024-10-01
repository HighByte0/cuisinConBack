<?php

namespace App\Http\Controllers\Api\V1\Auth;
use App\Models\User;

use Illuminate\Http\Request;

use App\CentralLogics\Helpers;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;


class CustomerAuthController extends Controller
{
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|min:6'
        ]);
    
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422); // 422 for validation errors
        }
    
        $credentials = $request->only('email', 'password');
    
        if (!Auth::attempt($credentials)) {
            return response()->json(['errors' => ['code' => 'auth-001', 'message' => 'Unauthorized.']], 401); // 401 for authentication failure
        }
    
        $user = Auth::user();
        if (!$user->status) {
            return response()->json(['errors' => ['code' => 'auth-003', 'message' => 'Your account is blocked.']], 403); // 403 for blocked account
        }
    
        $token = $user->createToken('RestaurantCustomerAuth')->plainTextToken;
    
        return response()->json(['token' => $token, 'is_phone_verified' => $user->is_phone_verified], 200); // 200 for success
    }
    
    
        public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'f_name' => 'required',
            //'l_name' => 'required',
            'email' => 'required|unique:users',
            'phone' => 'required|unique:users',
            'password' => 'required|min:6',
        ], [
            'f_name.required' => 'The first name field is required.',
            'phone.required' => 'The  phone field is required.',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => Helpers::error_processor($validator)], 403);
        }
        $user = User::create([
            'f_name' => $request->f_name,
            //'l_name' => $request->l_name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => bcrypt($request->password),
        ]);

        $token = $user->createToken('RestaurantCustomerAuth')->plainTextToken;

       
        return response()->json(['token' => $token,'is_phone_verified' => 0, 'phone_verify_end_url'=>"api/v1/auth/verify-phone" ], 200);
    }
}
