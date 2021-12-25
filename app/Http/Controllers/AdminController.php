<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use Illuminate\Http\Request;
use App\Models\User;
use Carbon\Carbon;

class AdminController extends Controller
{
    public function register(Request $request)
    {

        $user = Admin::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'password' => $request->password,
         ]);

        $token = auth('admin')->login($user);

        return $this->respondWithToken($token);
    }

    public function login()
    {
        $credentials = request(['email', 'password']);

        if (! $token = auth('admin')->attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        return $this->respondWithToken($token);
    }

    public function logout()
    {
        auth('admin')->logout();

        return response()->json(['message' => 'Successfully signed out']);
    }

    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type'   => 'bearer',
            'expires_in'   => auth('admin')->factory()->getTTL() * 60,
            'admin_id'      => auth('admin')->user()->first_name
        ]);
    }

    public function getAverage ($name){

        $date = Carbon::today();
        if($name === "last_24hours"){
            $date=Carbon::now()->subHours(24)->toDateTimeString();
            $count= User::where('created_at','>=',$date)->count();
            $average = ceil($count / 24);
            return response()->json([
                'users' => $count,
                'average' => $average,
            ]);
        }

        else if($name === "last_week"){
            $date = Carbon::now()->subWeek(1)->toDateTimeString();
            $count= User::where('created_at','>=',$date)->count();
            $average = ceil($count / 7);
            return response()->json([
                'users' => $count,
                'average' => $average,
            ]);
        }

        else if($name === "last_month"){
            $date = Carbon::now()->subMonth(1)->toDateTimeString();
            $count= User::where('created_at','>=',$date)->count();
            $average = ceil($count / (7 * 4 + 2));
            return response()->json([
                'users' => $count,
                'average' => $average,
            ]);
        }

        else if($name === "last_3months"){
            $date = Carbon::now()->subMonth(3)->toDateTimeString();
            $count= User::where('created_at','>=',$date)->count();
            $average = ceil($count / ((( 7 * 4 ) + 2)*3));
            return response()->json([
                'users' => $count,
                'average' => $average,
            ]);
        }

        else if($name === "last_year"){
            $date=Carbon::now()->subYear(1)->toDateTimeString();
            $count= User::where('created_at','>=',$date)->count();
            $average = ceil($count / ((( 7 * 4) + 2) * 12));
            return response()->json([
                'users' => $count,
                'average' => $average,
            ]);
        }
    }    
}
