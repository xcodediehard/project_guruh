<?php

namespace App\Http\Controllers;

use App\Mail\VerifyPassword;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class AdminController extends Controller
{
    public function index()
    {
        $data = [
            "title" => "Staff",
            "list" => User::latest()->get()
        ];
        return view("admin.contents.staff.template", compact('data'));
    }

    public function store(Request $request)
    {
        $request->validate([
            "name" => "required",
            "email" => "email|required|unique:users,email",
            "owner" => "required",
            "activate" => "required"
        ]);

        $list_value = [
            "name" => $request->name,
            "email" => $request->email,
            "is_owner" => $request->owner,
            "is_active" => $request->active,
            "forgot_password" => (string) Str::uuid(),
            "password" => bcrypt($request->password)
        ];

        $post = User::create($list_value);
        if ($post) {
            $list_email = [
                "name" => $request->name,
                "email" => $request->email,
                "address" => route("auth.verify", ["id" => $list_value['forgot_password']])
            ];
            Mail::to($request->email)->send(new VerifyPassword($list_email));
            session()->flash('success', 'Data berhasil dimasukan');
            return redirect()->route('merek.view');
        } else {
            session()->flash('error', 'Data gagal dimasukan');
            return redirect()->route('merek.view');
        }
    }

    public function destroy(User $staff)
    {
        $post = $staff->delete();

        if ($post) {
            session()->flash('success', 'Data berhasil dihapus');
            return redirect()->route('staff.view');
        } else {
            session()->flash('error', 'Data gagal dihapus');
            return redirect()->route('staff.view');
        }
    }
}
