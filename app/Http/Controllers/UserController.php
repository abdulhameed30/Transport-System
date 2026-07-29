<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function create()
    {
        $cities = DB::table('cities')->get();

        return view('manager.create-user', compact('cities'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'username' => 'required|unique:users',
            'password' => 'required',
            'role' => 'required',
        ], [
            'username.unique' => 'اسم المستخدم موجود مسبقاً، اختر اسماً آخر.',
        ]);

        User::create([
            'name' => $request->name,
            'username' => $request->username,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'city_id' => $request->city_id,
        ]);

        return redirect()->route('manager.users')
            ->with('success', 'تم إضافة المستخدم بنجاح');
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);
        $cities = DB::table('cities')->get();

        return view('manager.edit-user', compact(
            'user',
            'cities'
        ));
    }

    public function update(Request $request, $id)
    {

        $user = User::findOrFail($id);
        $request->validate([
            'name' => 'required',
            'username' => [
                'required',
                Rule::unique('users', 'username')->ignore($user->id),
            ],
            'role' => 'required',
        ], [
            'username.unique' => 'اسم المستخدم موجود مسبقاً، اختر اسماً آخر.',
        ]);

        $user->name = $request->name;
        $user->username = $request->username;
        $user->role = $request->role;
        $user->city_id = $request->city_id;

        // تحديث كلمة المرور فقط إذا تم إدخالها
        if ($request->password) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        return redirect()
            ->route('manager.users')
            ->with('success', 'تم تعديل المستخدم بنجاح');
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return redirect()
            ->route('manager.users')
            ->with('success', 'تم حذف المستخدم بنجاح');
    }
}
