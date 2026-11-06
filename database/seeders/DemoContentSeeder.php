<?php

namespace Database\Seeders;

use App\Models\Answer;
use App\Models\Category;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Lesson;
use App\Models\Question;
use App\Models\Quiz;
use App\Models\Review;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use RuntimeException;

class DemoContentSeeder extends Seeder
{
    /**
     * Jeu de données de démonstration strictement réservé à l'environnement local.
     * N'efface et ne remplace aucune donnée existante.
     */
    public function run(): void
    {
        if (! app()->environment('local')) {
            throw new RuntimeException(
                'DemoContentSeeder est réservé à APP_ENV=local. Aucune donnée n’a été modifiée.'
            );
        }

        $this->call(CategorySeeder::class);

        $formateur = $this->demoUser(
            'formateur',
            'Formateur Démonstration',
            'demo.formateur@learnhub.local',
            'formateur'
        );

        $students = collect([
            $this->demoUser('etudiant-1', 'Aminata Démo', 'demo.etudiant1@learnhub.local', 'etudiant'),
            $this->demoUser('etudiant-2', 'Ibrahima Démo', 'demo.etudiant2@learnhub.local', 'etudiant'),
            $this->demoUser('etudiant-3', 'Fatou Démo', 'demo.etudiant3@learnhub.local', 'etudiant'),
        ]);

        $catalogue = [
            [
                'title' => 'Maîtriser HTML5 et CSS3',
                'description' => 'Construisez des pages web accessibles et responsives en maîtrisant la structure HTML, les mises en page CSS et les bonnes pratiques du web.',
                'category' => 'developpement-web',
                'price' => 0,
                'published' => true,
                'archived' => false,
                'topic' => 'HTML et CSS',
            ],
            [
                'title' => 'JavaScript moderne : des bases au DOM',
                'description' => 'Apprenez les fondamentaux de JavaScript, les fonctions, les tableaux, les objets, les événements et la manipulation du DOM.',
                'category' => 'developpement-web',
                'price' => 0,
                'published' => true,
                'archived' => false,
                'topic' => 'JavaScript',
            ],
            [
                'title' => 'Développer une application avec Laravel',
                'description' => 'Découvrez les routes, contrôleurs, modèles Eloquent, migrations, validation et authentification avec Laravel.',
                'category' => 'developpement-web',
                'price' => 15000,
                'published' => true,
                'archived' => false,
                'topic' => 'Laravel',
            ],
            [
                'title' => 'MySQL : concevoir et interroger une base',
                'description' => 'Concevez un modèle relationnel, créez des tables et maîtrisez les requêtes SQL, jointures et index.',
                'category' => 'base-de-donnees',
                'price' => 10000,
                'published' => true,
                'archived' => false,
                'topic' => 'MySQL',
            ],
            [
                'title' => 'Créer des interfaces avec React',
                'description' => 'Créez des interfaces interactives avec les composants, props, state, hooks et appels API de React.',
                'category' => 'developpement-web',
                'price' => 15000,
                'published' => true,
                'archived' => false,
                'topic' => 'React',
            ],
            [
                'title' => 'Fondamentaux du design UX/UI',
                'description' => 'Apprenez à comprendre les utilisateurs, organiser une interface, créer des wireframes et construire un prototype cohérent.',
                'category' => 'design',
                'price' => 10000,
                'published' => true,
                'archived' => false,
                'topic' => 'UX/UI',
            ],
            [
                'title' => 'Initiation à la cybersécurité',
                'description' => 'Découvrez les principes de sécurité, la gestion des mots de passe, les risques courants et les réflexes de protection.',
                'category' => 'cybersecurite',
                'price' => 12000,
                'published' => false,
                'archived' => true,
                'topic' => 'Cybersécurité',
            ],
            [
                'title' => 'Docker et déploiement : premier parcours',
                'description' => 'Comprenez les conteneurs, les images Docker, les variables d’environnement et les étapes de préparation au déploiement.',
                'category' => 'devops',
                'price' => 20000,
                'published' => false,
                'archived' => false,
                'topic' => 'Docker',
            ],
        ];

        $courses = collect();

        foreach ($catalogue as $index => $item) {
            $category = Category::where('slug', $item['category'])->firstOrFail();

            $course = Course::firstOrCreate(
                [
                    'user_id' => $formateur->id,
                    'title' => $item['title'],
                ],
                [
                    'description' => $item['description'],
                    'thumbnail' => null,
                    'price' => $item['price'],
                    'is_published' => $item['published'],
                    'category_id' => $category->id,
                ]
            );

            if ($item['archived'] && ! $course->archived_at) {
                $course->forceFill([
                    'is_published' => false,
                    'archived_at' => now(),
                ])->save();
            }

            $courses->put($index, $course);

            $lessonTitles = [
                'Comprendre les notions essentielles',
                'Découvrir les outils et l’environnement',
                'Mettre en pratique avec un exercice guidé',
                'Réaliser un mini-projet et faire le bilan',
            ];

            foreach ($lessonTitles as $lessonIndex => $lessonTitle) {
                Lesson::firstOrCreate(
                    [
                        'course_id' => $course->id,
                        'order' => $lessonIndex + 1,
                    ],
                    [
                        'title' => $lessonTitle . ' — ' . $item['topic'],
                        'video_url' => null,
                        'duration' => [18, 24, 30, 35][$lessonIndex],
                    ]
                );
            }

            $quiz = Quiz::firstOrCreate(
                [
                    'course_id' => $course->id,
                    'title' => 'Évaluation — ' . $item['topic'],
                ],
                [
                    'description' => 'Vérifiez les connaissances acquises dans ce parcours.',
                    'pass_score' => 70,
                    'is_active' => true,
                ]
            );

            $questions = [
                [
                    'text' => 'Quel est le meilleur réflexe avant de commencer un nouveau projet ' . $item['topic'] . ' ?',
                    'answers' => [
                        ['text' => 'Comprendre le besoin et préparer son environnement', 'correct' => true],
                        ['text' => 'Ignorer les objectifs', 'correct' => false],
                        ['text' => 'Supprimer les fichiers existants', 'correct' => false],
                        ['text' => 'Éviter de tester le résultat', 'correct' => false],
                    ],
                ],
                [
                    'text' => 'Pourquoi est-il important de structurer son travail ?',
                    'answers' => [
                        ['text' => 'Pour rendre le projet plus difficile', 'correct' => false],
                        ['text' => 'Pour faciliter la lecture, la maintenance et les évolutions', 'correct' => true],
                        ['text' => 'Pour éviter toute documentation', 'correct' => false],
                        ['text' => 'Pour empêcher la collaboration', 'correct' => false],
                    ],
                ],
                [
                    'text' => 'Quelle démarche permet de vérifier qu’une fonctionnalité répond au besoin ?',
                    'answers' => [
                        ['text' => 'La tester avec des cas concrets', 'correct' => true],
                        ['text' => 'Se fier uniquement à son apparence', 'correct' => false],
                        ['text' => 'Ne jamais la faire essayer', 'correct' => false],
                        ['text' => 'Ignorer les retours', 'correct' => false],
                    ],
                ],
            ];

            foreach ($questions as $questionIndex => $questionData) {
                $question = Question::firstOrCreate(
                    [
                        'quiz_id' => $quiz->id,
                        'order' => $questionIndex + 1,
                    ],
                    ['question_text' => $questionData['text']]
                );

                foreach ($questionData['answers'] as $answerData) {
                    Answer::firstOrCreate(
                        [
                            'question_id' => $question->id,
                            'answer_text' => $answerData['text'],
                        ],
                        ['is_correct' => $answerData['correct']]
                    );
                }
            }
        }

        // Inscriptions variées pour tester les tableaux de bord et les règles d'accès.
        $enrollments = [
            [0, 0, 'free', 25],
            [0, 1, 'free', 50],
            [0, 2, 'paid', 25],
            [0, 6, 'paid', 25], // accès conservé à un cours archivé
            [1, 0, 'free', 50],
            [1, 3, 'paid', 25],
            [1, 5, 'paid', 0],
            [2, 1, 'free', 25],
            [2, 2, 'paid', 50],
            [2, 4, 'paid', 25],
            [2, 7, 'pending', 0],
        ];

        foreach ($enrollments as [$studentIndex, $courseIndex, $status, $progress]) {
            $student = $students->get($studentIndex);
            $course = $courses->get($courseIndex);

            $enrollment = Enrollment::firstOrCreate(
                [
                    'user_id' => $student->id,
                    'course_id' => $course->id,
                ],
                [
                    'enrolled_at' => now()->subDays(12 - $studentIndex),
                    'payment_status' => $status,
                    'progress' => $progress,
                ]
            );

            // Les données de progression ne sont ajoutées qu'aux inscriptions actives.
            if (in_array($enrollment->payment_status, ['free', 'paid'], true)) {
                $completedCount = (int) round(
                    $enrollment->progress * $course->lessons()->count() / 100
                );

                foreach ($course->lessons()->orderBy('order')->take($completedCount)->get() as $lesson) {
                    $lesson->completedByUsers()->syncWithoutDetaching([
                        $student->id => ['completed_at' => now()->subDays(2)],
                    ]);
                }

                if ($course->archived_at === null) {
                    Review::firstOrCreate(
                        [
                            'user_id' => $student->id,
                            'course_id' => $course->id,
                        ],
                        [
                            'rating' => 5,
                            'comment' => 'Un parcours clair et utile pour progresser étape par étape.',
                        ]
                    );
                }
            }
        }

        $this->command?->info('Données de démonstration LearnHub ajoutées sans supprimer les données existantes.');
        $this->command?->line('Comptes : demo.formateur@learnhub.local, demo.etudiant1@learnhub.local, demo.etudiant2@learnhub.local, demo.etudiant3@learnhub.local');
        $this->command?->line('Mot de passe initial commun : LearnHubDemo2026!');
    }

    private function demoUser(string $key, string $name, string $email, string $role): User
    {
        $user = User::firstOrCreate(
            ['email' => $email],
            [
                'name' => $name,
                'password' => Hash::make('LearnHubDemo2026!'),
                'role' => $role,
                'email_verified_at' => now(),
            ]
        );

        if ($user->role !== $role) {
            throw new RuntimeException(
                "Le compte {$email} existe déjà avec un rôle différent. Aucun compte existant n’a été modifié."
            );
        }

        return $user;
    }
}
