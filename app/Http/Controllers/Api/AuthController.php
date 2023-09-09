<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(RegisterRequest $request)
    {
        $data = $request->validated();

        $newUser = new User($data);
        $newUser->save();

        return response()->json([
            'status' => true,
            'message' => 'Register Success',
            'data' => new UserResource($newUser)
        ], 201);
    }

    public function login(Request $request)
    {
        $user = User::where('email', $request->input('email'))->first();
        if (!$user || !Hash::check($request->input('password'), $user->password)) {
            throw new HttpResponseException(response([
                'errors' => [
                    'message' => [
                        'email or password wrong',
                    ],
                ],
            ], 401));
        }
        $user->tokens()->delete();
        $token = $user->createToken('token')->plainTextToken;

        return response()->json([
            'status' => true,
            'message' => 'login has been successfuly',
            'token' => $token,
        ], 200);
    }

    public function me(Request $request)
    {
        return $request->user();
    }

    public function logout(Request $request)
    {
        $request->user()->tokens()->where('id', $request->user()->currentAccessToken()->id)->delete();

        return response()->json([
            'status' => true,
            'message' => 'logout success',
        ], 200);
    }

    public function updateUser(UpdateUserRequest $request)
    {
        $data = $request->validated();

        $user = User::find($request->user()->id);
        $user->name = $data['name'];
        $user->email = $data['email'];
        $user->save();

        $updateUser = User::find($request->user()->id);
        return response()->json([
            'status' => true,
            'message' => 'update user success',
            'data' => new UserResource($updateUser)
        ]);
    }
}
