<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

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
}
