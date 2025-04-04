<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Tymon\JWTAuth\Contracts\Providers\JWT;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        try {
            $validated =  $request->validate([
                'username' => 'required|string|max:255|unique:users',
                'email' => 'required|email|unique:users',
                'password' => 'required|min:6|string',
                'password_confirmation' => 'required|same:password',

            ], [
                'username.required' => 'Ten dang nhap khong duoc de trong',
                'email.required' => 'Email khong duoc de trong',
                'password.required' => 'Mat khau khong duoc de trong',
                'password_confirmation.required' => 'Xac nhan mat khau khong duoc de trong',

            ], [
                'username.unique' => 'Ten dang nhap da ton tai',
                'email.unique' => 'Email da ton tai',
                'password.min' => 'Mat khau phai co it nhat 6 ky tu',
                'password_confirmation.same' => 'Mat khau xac nhan khong khop',
            ]);

            $user = User::create([

                'email' => $request->email,
                'username' => $request->username,
                'full_name' => $request->username,
                'password' => Hash::make($request->password),
                'role' => 'user',
            ]);

            $token = JWTAuth::fromUser($user);

            return response()->json([
                'message' => 'User da dang ky thanh cong',
                'user' => $user,
                'token' => $token,
            ], 201);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['errors' => $e->validator->errors()->all(), 'message' => 'Loi validate: ' . $e->getMessage()], 422);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Something went wrong: ' . $e->getMessage()], 500);
        }
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email|string',
            'password' => 'required|string',
        ]);

        $credentials = $request->only('email', 'password');
        // if (!$token = JWTAuthAuth::attempt(['email' => $email, 'password' => $password]))
        if (!$token = JWTAuth::attempt($credentials)) {
            return response()->json(['error' => 'Dang nhap that bai'], 401);
        }
        return response()->json([
            'message' => 'Dang nhap thanh cong',
            'token' => $token,
        ]);
    }

    public function forgotPassword(Request $request)
    {
        try {
            $request->validate([
                'email' => 'required|email|string',
            ]);
            $user = User::where('email', $request->email)->first();
            if (!$user) {
                return response()->json(['error' => 'email not found'], 404);
            }

            $token = str_pad(rand(0, 999999), 6, '0', STR_PAD_LEFT);
            DB::table('password_reset_tokens')->updateOrInsert(
                ['email' => $request->email],
                ['token' => $token, 'created_at' => now()]
            );

            Mail::raw("Ma doi mat khau cua ban: $token", function ($message) use ($request) {
                $message->to($request->email)->subject('Password Reset Code');
            });
            return response()->json(['message' => 'Reset code sent to your email']);
        } catch (\Throwable $th) {
            return response()->json(['error' => $th->getMessage()], 500);
        }
    }

    public function resetPassword(Request $request)
    {
        try {
            $request->validate([
                'email' => 'required|email|string',
                'token' => 'required|string',
                'password' => 'required|string|min:6',
            ]);

            $reset = DB::table('password_reset_tokens')->where('email', $request->email)
                ->where('token', $request->token)->first();

            if (!$reset || Carbon::parse($reset->created_at)->diffInMinutes(now()) > 5) {
                return response()->json(['error' => 'Invalid or expired token'], 400);
            }

            $user = User::where('email', $request->email)->first();
            if ($user) {
                $user->update(['password' => Hash::make($request->password)]);
                DB::table('password_reset_tokens')->where('email', $request->email)->delete();
                return response()->json(['message' => 'password reset successfully']);
            }
            return response()->json(['error' => 'User not found'], 404);
        } catch (\Throwable $th) {
            return response()->json(['error' => $th->getMessage()], 500);
        }
    }
}
