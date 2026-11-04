<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Course;
use App\Models\Lesson;
use App\Models\Enrollment;
use App\Models\Review;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Créer des utilisateurs de test
        $admin = User::factory()->create([
            'name' => 'Admin Test',
            'email' => 'admin@example.com',
            'role' => 'admin',
        ]);

        $formateur = User::factory()->create([
            'name' => 'Formateur Test',
            'email' => 'formateur@example.com',
            'role' => 'formateur',
        ]);

        $etudiant = User::factory()->create([
            'name' => 'Étudiant Test',
            'email' => 'etudiant@example.com',
            'role' => 'etudiant',
        ]);

        $this->call(CategorySeeder::class);

        // Créer des cours de test
        $categories = \App\Models\Category::all();

        $courses = [
            [
                'title' => 'Introduction à Laravel',
                'description' => 'Apprenez les bases du framework Laravel pour créer des applications web robustes.',
                'price' => 49.99,
                'is_published' => true,
                'category_id' => $categories->where('slug', 'developpement-web')->first()->id,
                'user_id' => $formateur->id,
            ],
            [
                'title' => 'Design UX/UI Avancé',
                'description' => 'Maîtrisez les principes du design d\'interface utilisateur moderne.',
                'price' => 0,
                'is_published' => true,
                'category_id' => $categories->where('slug', 'design')->first()->id,
                'user_id' => $formateur->id,
            ],
            [
                'title' => 'Base de données MySQL',
                'description' => 'Comprendre et maîtriser les bases de données relationnelles.',
                'price' => 39.99,
                'is_published' => true,
                'category_id' => $categories->where('slug', 'base-de-donnees')->first()->id,
                'user_id' => $formateur->id,
            ],
            [
                'title' => 'Développement Mobile React Native',
                'description' => 'Créez des applications mobiles cross-platform avec React Native.',
                'price' => 79.99,
                'is_published' => false,
                'category_id' => $categories->where('slug', 'mobile')->first()->id,
                'user_id' => $formateur->id,
            ],
        ];

        foreach ($courses as $courseData) {
            $course = \App\Models\Course::create($courseData);

            // Créer quelques leçons pour chaque cours
            for ($i = 1; $i <= 3; $i++) {
                \App\Models\Lesson::create([
                    'course_id' => $course->id,
                    'title' => 'Leçon ' . $i . ' - ' . $course->title,
                    'video_url' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
                    'duration' => rand(10, 45),
                    'order' => $i,
                ]);
            }

            // Inscrire l'étudiant à quelques cours
            if ($course->is_published && rand(0, 1)) {
                \App\Models\Enrollment::create([
                    'user_id' => $etudiant->id,
                    'course_id' => $course->id,
                    'enrolled_at' => now()->subDays(rand(1, 30)),
                    'progress' => rand(0, 100),
                    'payment_status' => $course->price > 0 ? 'paid' : 'free',
                ]);

                // Marquer quelques leçons comme complétées
                $lessons = $course->lessons()->take(rand(0, 3))->get();
                foreach ($lessons as $lesson) {
                    $lesson->completedByUsers()->attach($etudiant->id, [
                        'completed_at' => now()->subDays(rand(1, 30)),
                    ]);
                }
            }

            // Ajouter quelques avis
            if (rand(0, 1)) {
                \App\Models\Review::create([
                    'user_id' => $etudiant->id,
                    'course_id' => $course->id,
                    'rating' => rand(3, 5),
                    'comment' => 'Très bon cours, je recommande !',
                ]);
            }
        }
    }
}
