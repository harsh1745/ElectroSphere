<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Check kar lein ki 'admins' table mein pehle se koi admin toh nahi hai
        // Hum 'email' par check kar rahe hain, assumption hai ki migration run ho chuki hai.
        if (DB::table('admins')->where('email', 'admin@example.com')->doesntExist()) {
            
            // ADMINS table mein data store karein
            // NOTE: 'name' FIELD HATA DIYA GAYA HAI KYUNKI WOH TABLE MEIN NAHI HAI
            DB::table('admins')->insert([
                'email' => 'admin@example.com', // Yahi aapka login email hoga
                'password' => Hash::make('password'), 
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);

            $this->command->info('Admin user created successfully in the "admins" table using email.');
        } else {
            $this->command->warn('Admin user already exists in "admins" table. Skipping.');
        }
    }
}
