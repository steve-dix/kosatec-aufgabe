<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class NotizensSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = \Faker\Factory::create();
		for($fakes=0,$fakes<50;$fakes++) {
			Notizens::create([
				'titel' => $data->sentence,
				'inhalt' => $data->longText,
				'wichtig' => $data->enum(['J','N']);
				'einstellungstermin' => $data->date,
			]);
		}
    }
}
