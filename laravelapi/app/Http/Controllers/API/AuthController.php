<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
//use Dotenv\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'name' => 'required|max:191',
            'email' => 'required|unique:users,email',
            'password' => 'required|min:4'
        ]);

        if($validator->fails())
        {
            return response()->json([
                'validation_errors'=>$validator->errors(),
            ]);
        }

        else
        {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);

            $token = $user->createToken($user->email.'_Token')->plainTextToken;

            return response()->json([
                'status'=>200,
                'username'=>$user->name,
                'token'=>$token,
                'message'=>'Registered Successfully',

            ]);
        }
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'email' => 'required',
            'password' => 'required|min:4'
        ]);

        if($validator->fails())
        {
            return response()->json([
                'validation_errors'=>$validator->errors(),
            ]);
        }

        else
        {
            $user = User::where('email', $request->email)->first();

            if (! $user || ! Hash::check($request->password, $user->password)) {
                return response()->json([
                    'status'=>401,
                    'message'=>'Invalid Credantials'
                ]);
            }
            else
            {
                $token = $user->createToken($user->email.'_Token')->plainTextToken;

                return response()->json([
                    'status'=>200,
                    'username'=>$user->name,
                    'token'=>$token,
                    'message'=>'Logged In Successfully',

                ]);
            }
        }
    }

    public function logout()
    {
        auth()->user()->tokens()->delete();
        return response()->json([
            'status' == 200,
            'message'=>'Logged Out Successfully',
        ]);
    }
}
