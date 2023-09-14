<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;


class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $categories_en=['resturants','houses','appliances','clothes','cars'];
        $categories_ar=['مطاعم','منازل','اجهزة منزلية','ملابس','عربيات'];

        foreach($categories_en as $key => $category){
            $data = [
                'en' => ['name' => $categories_en[$key]],
                'ar' => ['name' => $categories_ar[$key]],
            ];
            // dd($data);
            $category=Category::create($data);
            $categories_logos=['resturants.png','houses.png','appliances.jpg','clothes.jpg','cars.jpg'];
            $path='public/images/categories/'.$categories_logos[$key];
            $category->addMedia($path)->toMediaCollection('categories-logos');
            $new_path=$category->getFirstMedia('categories-logos')->getPath();
            File::copy($new_path,$path);
        }
    }
}
