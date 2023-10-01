<?php

namespace Database\Seeders;

use App\Models\Governorate;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class GovernorateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
           $governorates_en=['cairo','asuit','giza','alex','luxor','aswan'];
           $governorates_ar=['القاهرة','اسيوط','ألجيزة','اسكندرية','الاقصر','اسوان'];

           foreach($governorates_en as $key => $governorate){
              $data = [
                 'en' => ['name' => $governorates_en[$key]],
                 'ar' => ['name' => $governorates_ar[$key]],
               ];
            // dd($data);
            Governorate::create($data);
    }
}
}
