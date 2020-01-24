<?php

use Illuminate\Database\Seeder;

class PermissionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('permissions')->truncate();
        DB::table('permission_user')->truncate();

        DB::table('permissions')->insert([
            ['name' => 'Manage_Users'], //1
            ['name' => 'Add_Users'],          //2
            ['name' => 'Edit_Users'],         //3
            ['name' => 'Delete_Users'],       //4
        ]);
    }
}
