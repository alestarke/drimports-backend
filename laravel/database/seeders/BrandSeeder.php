<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Brand; 
use Illuminate\Support\Facades\DB; 
use Illuminate\Support\Str;

class BrandSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('brands')->truncate();

        $brands = [
            'Apple', 'Samsung', 'Dell', 'HP', 'Lenovo', 'Asus', 'Acer',
            'Microsoft', 'Sony', 'LG', 'Intel', 'AMD', 'Nvidia',
            'Kingston', 'Logitech', 'Razer', 'Corsair', 'Google',
            'Xiaomi', 'JBL', 'Panasonic', 'Philips', 'Western Digital',
            'Seagate', 'Gigabyte', 'Lexar', 'Msi', 'Redragon'
        ];

        foreach ($brands as $name) {
            Brand::create([
                'name' => $name,
                'slug' => Str::slug($name)
            ]);
        }
    }
}