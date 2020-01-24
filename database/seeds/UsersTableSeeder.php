<?php

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->truncate();

        DB::table('users')->insert([
            ['name' => 'Administrator', 'email' => 'a@a.com', 'role_id' => 1, 'password' => bcrypt('a')],
            ['name' => 'Client 1', 'email' => 'c1@c1.com', 'role_id' => 2, 'password' => bcrypt('c1')],
            ['name' => 'Client 2', 'email' => 'c2@c2.com', 'role_id' => 2, 'password' => bcrypt('c2')],
        ]);

        //Permissions User Administrator
        DB::table('permission_user')->insert([
            ['permission_id' => 1, 'user_id' => 1],
            ['permission_id' => 2, 'user_id' => 1],
            ['permission_id' => 3, 'user_id' => 1],
            ['permission_id' => 4, 'user_id' => 1],
        ]);

        // //Permissions User Client 1
        // DB::table('permission_user')->insert([
        //     ['permission_id' => 5, 'user_id' => 2],
        //     ['permission_id' => 6, 'user_id' => 2],
        //     ['permission_id' => 7, 'user_id' => 2],
        //     ['permission_id' => 8, 'user_id' => 2],
        //     ['permission_id' => 9, 'user_id' => 2],
        //     ['permission_id' => 10, 'user_id' => 2],
        //     ['permission_id' => 11, 'user_id' => 2],
        //     ['permission_id' => 12, 'user_id' => 2],
        //     ['permission_id' => 13, 'user_id' => 2],
        //     ['permission_id' => 14, 'user_id' => 2],
        //     ['permission_id' => 15, 'user_id' => 2],
        //     ['permission_id' => 16, 'user_id' => 2],
        // ]);

        // //Permissions User Client 2
        // DB::table('permission_user')->insert([
        //     ['permission_id' => 5, 'user_id' => 3],
        //     ['permission_id' => 6, 'user_id' => 3],
        //     ['permission_id' => 7, 'user_id' => 3],
        //     ['permission_id' => 8, 'user_id' => 3],
        //     ['permission_id' => 9, 'user_id' => 3],
        //     ['permission_id' => 10, 'user_id' => 3],
        //     ['permission_id' => 11, 'user_id' => 3],
        //     ['permission_id' => 12, 'user_id' => 3],
        //     ['permission_id' => 13, 'user_id' => 3],
        //     ['permission_id' => 14, 'user_id' => 3],
        //     ['permission_id' => 15, 'user_id' => 3],
        //     ['permission_id' => 16, 'user_id' => 3],
        // ]);
    }
}
