<?php 

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Education',
                'description' => 'Support educational initiatives, scholarships, and learning resources',
                'icon' => 'book',
            ],
            [
                'name' => 'Healthcare',
                'description' => 'Medical treatments, health programs, and wellness initiatives',
                'icon' => 'heart-pulse',
            ],
            [
                'name' => 'Disaster Relief',
                'description' => 'Emergency assistance for natural disasters and humanitarian crises',
                'icon' => 'life-buoy',
            ],
            [
                'name' => 'Poverty Alleviation',
                'description' => 'Fighting poverty through sustainable development programs',
                'icon' => 'hand-heart',
            ],
            [
                'name' => 'Environment',
                'description' => 'Environmental conservation and climate action projects',
                'icon' => 'leaf',
            ],
            [
                'name' => 'Animal Welfare',
                'description' => 'Protection and care for animals in need',
                'icon' => 'paw-print',
            ],
            [
                'name' => 'Arts & Culture',
                'description' => 'Supporting artistic expression and cultural preservation',
                'icon' => 'palette',
            ],
            [
                'name' => 'Community Development',
                'description' => 'Building stronger, more resilient communities',
                'icon' => 'users',
            ],
            [
                'name' => 'Human Rights',
                'description' => 'Protecting and promoting fundamental human rights',
                'icon' => 'scale',
            ],
            [
                'name' => 'Food Security',
                'description' => 'Fighting hunger and ensuring access to nutritious food',
                'icon' => 'utensils',
            ],
        ];

        foreach ($categories as $category) {
            Category::create([
                'name' => $category['name'],
                'slug' => Str::slug($category['name']),
                'description' => $category['description'],
                'icon' => $category['icon'],
            ]);
        }
    }
}