<?php

namespace Database\Seeders;

use App\Models\Team;
use Illuminate\Database\Seeder;

class TeamSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $data = [
            'Adana Demirspor',
            'Alanyaspor',
            'Antalyaspor',
            'Beşiktaş',
            'Çaykur Rizespor',
            'Fatih Karagümrük',
            'Fenerbahçe',
            'Galatasaray',
            'Gaziantep FK',
            'Hatayspor',
            'İstanbul Başakşehir',
            'İstanbulspor',
            'Kasımpaşa',
            'Kayserispor',
            'Konyaspor',
            'MKE Ankaragücü',
            'Pendikspor',
            'Samsunspor',
            'Sivasspor',
            'Trabzonspor',
        ];

        foreach($data as $tn ){
            Team::create(['name' => $tn ,'power'=> rand(80,100)]);
        }
    }
}
