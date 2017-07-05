<?php

use Illuminate\Database\Seeder;
use App\User;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      $user = new User();
      $user->name = 'admin';
      $user->username = 'admin';
      $user->password = bcrypt('123456');
      $user->api_token = str_random(60);
      $user->unker = '';
      $user->nm_unker = '';
      $user->save();
    }
}
