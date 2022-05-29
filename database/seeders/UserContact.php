<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class UserContact extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    private function uuid_generator()
    {
        return (string) Str::uuid();
    }
    public function run()
    {
        $user_owner = [
            "name" => "owner",
            "email" => "owner@owner.com",
            "is_active" => "1",
            "is_owner" => "1",
            "forgot_password" => $this->uuid_generator(),
            "password" => bcrypt("123456")
        ];
        User::create($user_owner);
        $user_staff = [
            "name" => "staff",
            "email" => "staff@staff.com",
            "is_active" => "1",
            "is_owner" => "0",
            "forgot_password" => $this->uuid_generator(),
            "password" => bcrypt("123456")
        ];
        User::create($user_staff);
    }
}
