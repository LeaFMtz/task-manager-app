<?php

namespace Database\Seeders;

use App\Models\Task;
use App\Models\User;
use Illuminate\Database\Seeder;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class TaskSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = User::where('email', 'admin@test.com')->firstOrFail();
        $editor = User::where('email', 'editor@test.com')->firstOrFail();
        $viewer = User::where('email', 'viewer@test.com')->firstOrFail();

        Task::create([
            'user_id' => $admin->id,
            'title' => 'Revisar métricas de la semana',
            'description' => 'Generar el reporte de performance semanal.',
            'status' => 'pending',
            'due_date' => now()->addDays(3)
        ]);

        Task::create([
            'user_id' => $editor->id,
            'title' => 'Escribir post para el blog',
            'description' => 'Post sobre el lanzamiento del nuevo producto.',
            'status' => 'in_progress',
            'due_date' => now()->addDays(2)
        ]);

        Task::create([
            'user_id' => $editor->id,
            'title' => 'Preparar campaña de email',
            'description' => 'Segmentar la lista y diseñar el template.',
            'status' => 'pending',
            'due_date' => now()->addDays(5)
        ]);

        Task::create([
            'user_id' => $viewer->id,
            'title' => 'Completar curso de capacitación',
            'description' => 'Terminar el módulo 3 de seguridad.',
            'status' => 'completed',
            'due_date' => now()->subDay()
        ]);
    }
}