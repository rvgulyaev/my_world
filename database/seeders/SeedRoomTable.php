<?php

namespace Database\Seeders;

use App\Models\Room;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SeedRoomTable extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $names = ["Кабинет логопеда","Кабинет дефектолога", "Кабинет психолога"];
                    foreach ($names as $name) {
                        Room::create(['name' => $name,]);
                    }
    }
}
