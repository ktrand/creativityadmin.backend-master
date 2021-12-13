<?php

use App\Modules\Auth\Models\User;
use Illuminate\Database\Seeder;

class CreateAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'name' => 'admin',
            'email' => 'admin@admin.site',
            'password' => bcrypt('123456'),
            'status' => 'admin'
        ]);
    }
}
