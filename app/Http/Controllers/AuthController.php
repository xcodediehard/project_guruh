<?php

namespace App\Http\Controllers;

use App\Models\Pelanggan;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    //ADMIN

    public function login_admin()
    {
        $data = ["title" => "Login"];
        return view("auth.admin.login", compact("data"));
    }

    public function process_login_admin(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
            'password' => 'required|min:6'
        ]);

        $auth = ["email" => $request->email, 'password' => $request->password];
        if (Auth::guard('admin')->attempt($auth)) {
            return redirect()->route("staff.view");
        } else {
            dd("gagal");
        }
    }
    public function forgot_password_admin()
    {
        $data = ["title" => "Forgot Password"];
        return view("auth.admin.forgot_password", compact("data"));
    }
    public function verify_admin()
    {
        $data = ["title" => "Verify Password"];
        return view("auth.admin.verify", compact("data"));
    }

    public function logout_admin()
    {
        Auth::guard('admin')->logout();
        return redirect()->route('auth.login');
    }

    // USER
    public function login_user()
    {
        $data = ["title" => "Login"];
        return view("auth.client.login", compact("data"));
    }

    public function process_login_user(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:pelanggans,email',
            'password' => 'required|min:8'
        ]);

        $auth = ["email" => $request->email, 'password' => $request->password];
        if (Auth::guard('client')->attempt($auth)) {
            return redirect()->route("home");
        } else {
            return redirect()->route("user.login");
        }
    }
    public function forgot_password_user()
    {
        $data = ["title" => "Forgot Password"];
        return view("auth.client.forgot_password", compact("data"));
    }
    public function validation_user()
    {
        $data = ["title" => "Verify Password"];
        return view("auth.client.verify", compact("data"));
    }
    public function register_user()
    {
        $data = ["title" => "Register"];
        return view("auth.client.register", compact("data"));
    }

    public function process_register(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|unique:pelanggans,email|email',
            // 'telpon' => 'required|unique:pelanggans,telpon',
            'alamat' => 'required',
            'password' => 'required|min:8',
            'password_confirmation' => 'required|min:8',
        ]);

        $format = [
            'name' => $request->name,
            'email' => $request->email,
            'telpon' => $request->telpon,
            'alamat' => $request->alamat,
            'password' => bcrypt($request->password),
            'forgot_password' => (string) Str::uuid()
        ];

        $post = Pelanggan::create($format);
        if ($post) {
            session()->flash('success', 'Data berhasil dimasukan');
            return redirect()->route('user.login');
        } else {
            session()->flash('error', 'Data gagal dimasukan');
            return redirect()->route('user.login');
        }
    }

    public function logout_user()
    {
        Auth::guard('client')->logout();
        return redirect()->route('user.login');
    }
}
