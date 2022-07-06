<?php

namespace App\Http\Controllers\API;

use Exception;
use App\Models\User;
use Illuminate\Http\Request;
use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function register(Request $request)
    {

        // dd($request->all());
        try{
            //validator
            $validator = Validator::make($request->all(),[
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users',
                'password' => 'required|string|min:6',
            ]);

            
            $kuser = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => 'user',
            ]);

            // dd($kuser);
            
    
            $user = User::where('email', $request->email)->first();
    
            $tokenResult = $user->createToken('authToken')->plainTextToken;
            // dd($tokenResult);

            return ResponseFormatter::success([
                'access_token' => $tokenResult,
                'token_type' => 'Bearer',
                'user' => $user,
            ], 'Berhasil mendaftar');

            if ($validator->fails()) {
                return ResponseFormatter::error($validator->errors()->first());
            }

        } catch(Exception $error) {
            return ResponseFormatter::error([
                'message' => 'Something went wrong',
                $error->getMessage(),
            ], 'Gagal mendaftar', 500);
        }

    }

    public function login(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'email' => 'required|string|email|max:255',
                'password' => 'required|string|min:6',
            ]);

            $credentials = request([
                'email',
                'password',
            ]);
            if(!Auth::attempt($credentials)) {
                return ResponseFormatter::error([
                    'message' => 'Invalid credentials',
                ], 'Gagal login', 401);
            }

            $user = User::where('email', $request->email)->first();

            if(!Hash::check($request->password, $user->password, [])) {
                throw new \Exception('Password is incorrect');
            }

            $tokenResult = $user->createToken('authToken')->plainTextToken;
                return ResponseFormatter::success([
                    'access_token' => $tokenResult,
                    'token_type' => 'Bearer',
                    'user' => $user,
                ], 'Berhasil login');


        } catch(Exception $error) {
            return ResponseFormatter::error([
                'message' => 'Something went wrong',
                'error' => $error,
            ], 'Gagal Login', 500);
        }
    }
}
