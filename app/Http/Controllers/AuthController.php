<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function index()
    {
        dump("test");
        if (Auth::check() == true) {
            return redirect()->route('dashboard');
        }
        return view('auth.index');
    }

    public function proses(Request $request)
    {
        $email = $request->email;
        $password = $request->password;

        $user = User::where('email', $email)->first();
        if ($user) {
            if (Hash::check($password, $user->password)) {
                Auth::attempt(['email' => $email, 'password' => $password]);
                if (Auth::check() == true) {
                    return redirect()->route('dashboard');
                }
            } else {
                session()->flash('pesan', '<div class="alert alert-warning alert-dismissible fade show" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            <strong>Password salah !!!</strong>
        </div>');
                return redirect()->route('login');
            }
        } else {
            session()->flash('pesan', '<div class="alert alert-warning alert-dismissible fade show" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            <strong>Email tidak ditemukan !!!</strong>
        </div>');
            return redirect()->route('login');
        }
    }

    public function regis()
    {
        $user = User::create([
            'name' => 'suryo',
            'email' => "admin@gmail.com",
            'password' => Hash::make('123456'),
        ]);

        //$user->assignRole('member');
        // Member_model::create([
        //     'id_users_login' =>  $user->id,
        //     'username' => $data['name'],
        //     'password' => $data['password'],
        //     'email' => $data['email'],
        //     'emailtanpatitik' => $data['email']
        // ]);


        return $user;
    }

    public function logout()
    {
        Auth::logout();
        session()->flash('pesan', '<div class="alert alert-info alert-dismissible fade show" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            <strong>Logout berhasil !!!</strong>
        </div>');
        return redirect()->route('login');
    }
}
