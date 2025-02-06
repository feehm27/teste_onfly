<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OrderTravelStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('order_travel_status')->insertOrIgnore([
            'id' => 1,
            'status' => 'Solicitado',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);

        DB::table('order_travel_status')->insertOrIgnore([
            'id' => 2,
            'status' => 'Aprovado',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);

        DB::table('order_travel_status')->insertOrIgnore([
            'id' => 3,
            'status' => 'Cancelado',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);
    }
}
