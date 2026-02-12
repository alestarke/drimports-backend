<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB; 
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('categories')->truncate();

        $categories = [
            'Notebooks',
            'Smartphones',
            'Tablets',
            'Acessórios',
            'Monitores',
            'Impressoras',
            'Componentes de PC',
            'Periféricos',
            'Redes',
            'Armazenamento',
            'Software',
            'Gadgets',
            'Câmeras',
            'Fones de Ouvido',
            'Smartwatches'
        ];

        foreach ($categories as $category) {
            Category::create([
                'name' => $category,
                'slug' => Str::slug($category)
            ]);
        }
    }
}