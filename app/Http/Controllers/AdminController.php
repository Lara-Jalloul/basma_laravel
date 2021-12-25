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

    public function getAverage (Request $request){

        $res = $request->input('name');

        $date = Carbon::today();
        if($res === "last_24hours"){
            $date=Carbon::now()->subHours(24)->toDateTimeString();
            $count= User::where('created_at','>=',$date)->count();
            $average = ceil($count / 24);
            return response()->json([
                'users' => $count,
                'average' => $average,
            ]);
        }

        else if($res === "last_week"){
            $date = Carbon::now()->subWeek(1)->toDateTimeString();
            $count= User::where('created_at','>=',$date)->count();
            $average = ceil($count / 7);
            return response()->json([
                'users' => $count,
                'average' => $average,
            ]);
        }

        else if($res === "last_month"){
            $date = Carbon::now()->subMonth(1)->toDateTimeString();
            $count= User::where('created_at','>=',$date)->count();
            $average = ceil($count / (7 * 4 + 2));
            return response()->json([
                'users' => $count,
                'average' => $average,
            ]);
        }

        else if($res === "last_3months"){
            $date = Carbon::now()->subMonth(3)->toDateTimeString();
            $count= User::where('created_at','>=',$date)->count();
            $average = ceil($count / ((( 7 * 4 ) + 2)*3));
            return response()->json([
                'users' => $count,
                'average' => $average,
            ]);
        }

        else if($res === "last_year"){
            $date=Carbon::now()->subYear(1)->toDateTimeString();
            $count= User::where('created_at','>=',$date)->count();
            $average = ceil($count / ((( 7 * 4) + 2) * 12));
            return response()->json([
                'users' => $count,
                'average' => $average,
            ]);
        }
    }
    
    public function filter (Request $request){

        $id = $request->input('id');
        $fname = $request->input('first_name');
        $email = $request->input('email');
        $nb_pagination = $request['nb'];

        if($id){
           $user = User::where('id','LIKE','%'.$id.'%')->paginate($nb_pagination);
           return response()->json([
               'users' => $user,
            ]);
        }

        else if($fname){
            $user = User::where('first_name','LIKE','%'.$fname.'%')->paginate($nb_pagination);
            return response()->json([
                'users' => $user,
             ]);
        }

        else if($email){
            $user = User::where('email','LIKE','%'.$email.'%')->paginate($nb_pagination);
            return response()->json([
                'users' => $user,
            ]);
        }
        else{
            $res = User::paginate($nb_pagination);
            return response()->json([
                'users' => $res,
             ]);
        }
    }
}
