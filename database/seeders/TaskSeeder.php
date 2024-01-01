<?php

namespace Database\Seeders;

use App\Models\Task;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Date;

class TaskSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Task::create(['task'=>'Подготовить карточки ПЕКС «Много» «Мало» для Корчагина Артема', 'executeDate'=>'2023-01-03 00:00:00', 'executed'=>0, 'comments'=>'', 'created_by'=>1, 'updated_by'=>1]);
    }
}
