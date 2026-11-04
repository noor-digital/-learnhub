<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
{
    $categories = [
        ['name' => 'Développement Web',  'slug' => 'developpement-web'],
        ['name' => 'Design',             'slug' => 'design'],
        ['name' => 'Base de données',    'slug' => 'base-de-donnees'],
        ['name' => 'DevOps',             'slug' => 'devops'],
        ['name' => 'Mobile',             'slug' => 'mobile'],
        ['name' => 'Cybersécurité',      'slug' => 'cybersecurite'],
        ['name' => 'Intelligence artificielle', 'slug' => 'ia'],
    ];

    foreach ($categories as $cat) {
        \App\Models\Category::firstOrCreate(
            ['slug' => $cat['slug']],
            ['name' => $cat['name']]
        );
    }
}
}
