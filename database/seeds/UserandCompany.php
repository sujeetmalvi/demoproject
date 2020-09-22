<?php

use Illuminate\Database\Seeder;

class UserandCompany extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //

        DB::table('users')->insert([
            'id' => 1,
            'name' => 'mukesh',
            'email' => 'mukesh@gmail.com',
            'password' => '$2y$10$voq9LGvuA9WmG2YkHhUiF.L1.zzJRIT/H20hI.11iqvLb5rFKHppS',
            'created_at' => '2020-07-09 13:26:01',
            'updated_at' => '2020-07-09 13:26:01',
            'company_id' => 1,
            'role_id' => 1
        ]);

        DB::table('company')->insert([
            'id' => 1,
            'company_name' => 'Company A'
        ]);
    }
}
