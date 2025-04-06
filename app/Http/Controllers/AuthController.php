<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    /**
     * @throws ValidationException
     */
    public function login(LoginRequest $request)
    {

        $user = User::query()->where('name', $request->name)->first();
        if (!$user || !Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'name' => ['نام کاربری یا رمز عبور اشتباه است.'],
            ]);

        }

        $user->tokens()->delete();

        return response()->json([
            'name' => $user->name,
            'role' => $user->role,
            'token' => $user->createToken('my-app-token')->plainTextToken,
        ]);
    }

    public function register(RegisterRequest $request)
    {
        User::query()->create([
            'name'=>$request->name,
            'password'=>bcrypt($request->password)
        ]);
        return response()->json([
            'message'=>'حساب شما با موفقیت ایجاد شد!'
        ]);
    }

    public function logout()
    {
        request()->user()->tokens()->delete();

        return response()->json([
            'message'=>'با موفقیت خارج شدید.'
        ]);
    }
}
