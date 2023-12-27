<?php

namespace Database\Seeders;

use App\Models\Classes;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ClassesSeed extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $names = ['АВА', 'Дефектолог', 'Логопед', 'АФК', 'СИ', 'МС', 'Денвер', 'Групповые занятия', 'Каллиграфия', 'Печатание', 'Психолог', 'Нейропсихолог', 'Школа', 'Прочие'];
        foreach ($names as $name) {
            Classes::create(['name' => $name,]);
        }
    }
}
