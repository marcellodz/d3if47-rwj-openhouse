<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminAuthController extends Controller
{
    /*
    =========================
    LOGIN PAGE
    =========================
    */

    public function showLogin()
    {
        return view('admin.auth.login');
    }

    /*
    =========================
    LOGIN ACTION
    =========================
    */

    public function login(Request $request)
    {
        $username = trim($request->username);

        $password = $request->password;

        $admin = DB::table('admin_user')
            ->where('username', $username)
            ->first();

        /*
        =========================
        USERNAME TIDAK ADA
        =========================
        */

        if (!$admin) {

            return back()
                ->with('error', 'Username tidak ditemukan!');
        }

        /*
        =========================
        PASSWORD SALAH
        =========================
        */

        if ($password !== $admin->password) {

            return back()
                ->with('error', 'Password salah!');
        }

        /*
        =========================
        SESSION LOGIN
        =========================
        */

        session([

            'admin_loggedin' => true,

            'admin_id' => $admin->id_admin,

            'username' => $admin->username,

            'role' => $admin->role

        ]);

        /*
        =========================
        UPDATE LAST LOGIN
        =========================
        */

        DB::table('admin_user')
            ->where('id_admin', $admin->id_admin)
            ->update([
                'last_login' => now()
            ]);

        return redirect('/admin');
    }

    /*
    =========================
    LOGOUT
    =========================
    */

    public function logout(Request $request)
    {
        $request->session()->flush();

        return redirect('/admin/login');
    }
}