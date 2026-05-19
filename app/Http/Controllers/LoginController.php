<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LoginController extends Controller
{
    public function authenticate(Request $request)
    {

        /* ================================
           VALIDASI
        ================================ */

        $email = trim($request->email ?? '');
        $password = trim($request->password ?? '');

        if(empty($email) || empty($password)){

            return redirect('/login?err=empty');

        }

        $password_hash = md5($password);

        /* ================================
           CEK USER
        ================================ */

        $user = DB::table('super_user')
            ->where('email', $email)
            ->where('password', $password_hash)
            ->first();

        if($user){

            if($user->aktivasi === 'Y'){

                /* ================================
                   SESSION LOGIN
                ================================ */

                session([

                    'iduser' => $user->iduser,
                    'nama' => $user->nama,
                    'email' => $user->email,
                    'hp' => $user->hp,

                    'loggedin' => true,

                    'start_time' => time()

                ]);

                return redirect('/dashboard');

            }else{

                return redirect('/login?err=inactive');

            }

        }

        return redirect('/login?err=wrong');
    }
}