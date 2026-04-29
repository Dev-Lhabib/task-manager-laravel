<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Category;
use App\Models\Task;

class DatabaseSeeder extends Seeder
{
    // Note : on utilise firstOrCreate pour éviter les doublons si on relance le seeder
    public function run(): void
    {
        // 4 catégories fixes
        $categories = [
            'Développement',
            'Design',
            'Marketing',
            'DevOps',
        ];
        foreach ($categories as $name) {
            Category::firstOrCreate(['name' => $name]);
        }
        // Ajoute 10 catégories aléatoires
        \App\Models\Category::factory()->count(10)->create();

        // 2 utilisateurs
        $alice = User::firstOrCreate(
            ['email' => 'alice@example.com'],
            ['name' => 'Alice Martin', 'password' => Hash::make('password')]
        );

        $bob = User::firstOrCreate(
            ['email' => 'bob@example.com'],
            ['name' => 'Bob Dupont', 'password' => Hash::make('password')]
        );

        // 10 tâches fixes — 6 pour Alice, 4 pour Bob
        $tasks = [
            // Alice
            ['title' => 'Créer la page d\'accueil', 'description' => 'Design et intégration HTML/CSS', 'status' => 'done',        'category_id' => 1, 'user_id' => $alice->id],
            ['title' => 'Configurer Laravel Telescope', 'description' => 'Installation et configuration', 'status' => 'done',      'category_id' => 4, 'user_id' => $alice->id],
            ['title' => 'Implémenter l\'authentification', 'description' => 'Login, register, logout avec Breeze', 'status' => 'in_progress', 'category_id' => 1, 'user_id' => $alice->id],
            ['title' => 'Créer les migrations', 'description' => 'Tables categories et tasks', 'status' => 'in_progress',         'category_id' => 1, 'user_id' => $alice->id],
            ['title' => 'Rédiger le README', 'description' => 'Instructions d\'installation complètes', 'status' => 'todo',        'category_id' => 3, 'user_id' => $alice->id],
            ['title' => 'Déployer en production', 'description' => 'Configuration serveur et CI/CD', 'status' => 'todo',           'category_id' => 4, 'user_id' => $alice->id],

            // Bob
            ['title' => 'Créer le logo', 'description' => 'Logo SVG responsive', 'status' => 'done',                             'category_id' => 2, 'user_id' => $bob->id],
            ['title' => 'Campagne emailing', 'description' => 'Newsletter Q2 2025', 'status' => 'in_progress',                    'category_id' => 3, 'user_id' => $bob->id],
            ['title' => 'Optimiser les requêtes SQL', 'description' => 'Corriger les N+1 détectés avec Debugbar', 'status' => 'todo', 'category_id' => 1, 'user_id' => $bob->id],
            ['title' => 'Intégrer les maquettes', 'description' => 'Passage des maquettes Figma en Blade', 'status' => 'todo',    'category_id' => 2, 'user_id' => $bob->id],
        ];

        foreach ($tasks as $task) {
            // On utilise firstOrCreate pour éviter les doublons si on relance le seeder
            Task::firstOrCreate(
                [
                'title' => $task['title'],
                'user_id' => $task['user_id']
            ],
            $task);
        }

        // Ajoute 50 tâches aléatoires
        \App\Models\Task::factory()->count(50)->create();
    }
}
