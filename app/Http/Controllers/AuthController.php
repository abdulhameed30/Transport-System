<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login(Request $request)
    {

        $user = User::where('username',$request->username)
            ->first();


        if(!$user || !Hash::check($request->password,$user->password))
        {
            return back()->with('error','بيانات الدخول غير صحيحة');
        }


        session([
            'user_id'=>$user->id,
            'name'=>$user->name,
            'role'=>$user->role,
            'city_id'=>$user->city_id
        ]);


        if($user->role == 'manager')
        {
            return redirect('/manager');
        }

        if($user->role == 'Ticket_Officer')
        {
            return redirect('/ticket-officer');
        }
        if($user->role == 'Movement_Officer')
        {
            return redirect('/movement-officer');
        }
        if($user->role == 'Driver')
        {
            return redirect('/driver');
        }


        return redirect('/');
    }



    public function logout()
    {
        session()->flush();

        return redirect()->route('login');
    }
}
