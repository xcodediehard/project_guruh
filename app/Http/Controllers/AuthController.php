<?php

namespace App\Http\Controllers;

use App\Embed\Midtrans\StatusTransactionService;
use App\Models\DetailBarang;
use App\Models\DetailTransaksi;
use App\Models\Pelanggan;
use App\Models\Transaksi;
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

    private function validation_transaction()
    {
        $transaction = Transaksi::where("keterangan", "=", "transaction")->latest()->get();

        $transaction_map = collect($transaction)->map(function ($transaction_record) {
            $statusTransaction = new StatusTransactionService($transaction_record->payment_id);
            $getStatusTransaction = $statusTransaction->getstatus();
            if ($getStatusTransaction->transaction_status == "expire" || $getStatusTransaction->transaction_status == "cancel") {
                Transaksi::where("id", "=", $transaction_record->id)->update([
                    "keterangan" => "cancel"
                ]);
                $detail_list = DetailTransaksi::where("payment_id", "=", $transaction_record->payment_id)->latest()->get();
                $detail_transaction = collect($detail_list)->map(function ($detail_record) {
                    $detail_barang = DetailBarang::where("id", "=", $detail_record->id_detail_barang);
                    $list_detail_barang = $detail_barang->first();
                    $detail_barang->update([
                        "stok" => ($list_detail_barang->stok + $detail_record->jumlah)
                    ]);
                });
            }
        });
    }
    public function process_login_user(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:pelanggans,email',
            'password' => 'required|min:8'
        ]);

        $auth = ["email" => $request->email, 'password' => $request->password];
        if (Auth::guard('client')->attempt($auth)) {
            $this->validation_transaction();
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
