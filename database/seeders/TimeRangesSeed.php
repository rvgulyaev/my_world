<?php

namespace Database\Seeders;

use App\Models\TimeRange;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TimeRangesSeed extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $names = [
"8:00-8:45","8:45-9:30","9:30-10:15","10:15-11:00","11:00-11:45","11:45-12:30","12:30-13:15",
"13:15-14:00","14:00-14:45","14:45-15:30","15:30-16:15","16:15-17:00","17:00-17:45","17:45-18:30",
"18:30-19:15","19:15-20:00"
        ];
        foreach ($names as $name) {
            TimeRange::create(['name' => $name,]);
        }
    }
}
