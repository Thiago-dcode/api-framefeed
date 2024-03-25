<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Traits\HttpResponses;
use Laravel\Sanctum\HasApiTokens;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class SessionController extends Controller
{
    use HttpResponses,HasApiTokens;

    public function store(Request $request)
    { {
            $fields = $request->validate([

                'username' => 'required|min:3|max:255|alpha_dash',

                'password' => 'required|min:8|max:255',


            ]);

            $user = User::Where('username', $fields['username'])->first();

            if (!$user || !Hash::check($fields['password'], $user->password)) {

                return response()->json(['message' => "Sorry, we can't find an account with this username. "], 401);
            }

            $token = $user->createToken('user token')->plainTextToken;

            return response()->json([
                'message' => 'Successfully logged in!.',
                'user' => $user,
                'token' => $token
            ], 201);
        }
    }
    public function destroy(Request $request )
    {
        auth()->user()->tokens()->delete();

        return response([
            'message' => 'Logged out successfully'
        ]);


    }
}
