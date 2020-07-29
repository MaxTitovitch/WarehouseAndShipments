<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = new User();
        $user->name = env('ADMIN_NAME');
        $user->email = env('ADMIN_EMAIL');
        $user->password = Hash::make(env('ADMIN_PASSWORD'));
        $user->save();
    }
}
