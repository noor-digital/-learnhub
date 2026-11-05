<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class CourseController extends Controller
{
   use AuthorizesRequests;

    // ─────────────────────────────────────────────────────
    // index() — Liste des cours publiés (catalogue)
    // ─────────────────────────────────────────────────────
    public function index(Request $request)
    {
        $query = Course::with(['formateur', 'category'])
            ->withCount(['students', 'reviews'])
            ->withAvg('reviews', 'rating')
            ->where('is_published', true);

        // Filtre par catégorie si sélectionnée
        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        // Recherche par titre
        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        $courses    = $query->latest()->paginate(12)->withQueryString();
        $categories = Category::orderBy('name')->get();

        return view('courses.index', compact('courses', 'categories'));
    }

    // ─────────────────────────────────────────────────────
    // create() — Formulaire de création
    // ─────────────────────────────────────────────────────
    public function create()
    {
        $this->checkRole(['admin', 'formateur']);

        $categories = Category::orderBy('name')->get();

        return view('courses.create', compact('categories'));
    }

    // ─────────────────────────────────────────────────────
    // store() — Enregistrement en base de données
    // ─────────────────────────────────────────────────────
    public function store(Request $request)
    {
        $this->checkRole(['admin', 'formateur']);

        $validated = $request->validate([
            'title'       => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'category_id' => ['required', 'exists:categories,id'],
            'price'       => ['required', 'numeric', 'min:0'],
            'thumbnail'   => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            // is_published n'est pas dans validate() car une checkbox
            // non cochée n'envoie rien — on la gère manuellement ci-dessous
        ]);

        // ⚠ CORRECTION is_published :
        // Une checkbox non cochée n'est PAS envoyée par le navigateur.
        // On utilise boolean() pour obtenir true/false proprement.
        $validated['is_published'] = $request->boolean('is_published');

        // Upload de l'image
        if ($request->hasFile('thumbnail')) {
            $validated['thumbnail'] = $request->file('thumbnail')
                ->store('thumbnails', 'public');
        }

        // Le cours appartient au formateur connecté
        $validated['user_id'] = auth()->id();

        $course = Course::create($validated);

        return redirect()
            ->route('courses.index')
            ->with('success', '🎉 Le cours "' . $course->title . '" a été créé avec succès !');
    }

    // ─────────────────────────────────────────────────────
    // show() — Détail d'un cours
    // ─────────────────────────────────────────────────────
    public function show(Course $course)
    {
        // Un cours non publié est visible uniquement par son auteur ou un admin
        if (! $course->is_published) {
            if ((int) auth()->id() !== (int) $course->user_id && ! auth()->user()->isAdmin()) {
                abort(403, 'Ce cours n\'est pas encore publié.');
            }
        }

        // Charge les relations pour éviter les N+1 queries
        $course->load(['formateur', 'category']);

        return view('courses.show', compact('course'));
    }

    // ─────────────────────────────────────────────────────
    // edit() — Formulaire d'édition
    // ─────────────────────────────────────────────────────
    public function edit(Course $course)
    {
        $this->authorize('update', $course);

        $categories = Category::orderBy('name')->get();

        return view('courses.edit', compact('course', 'categories'));
    }

    // ─────────────────────────────────────────────────────
    // update() — Mise à jour en base de données
    // ─────────────────────────────────────────────────────
    public function update(Request $request, Course $course)
    {
        $this->authorize('update', $course);

        $validated = $request->validate([
            'title'       => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'category_id' => ['required', 'exists:categories,id'],
            'price'       => ['required', 'numeric', 'min:0'],
            'thumbnail'   => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ]);

        $validated['is_published'] = $request->boolean('is_published');

        // Upload de l'image si nouvelle
        if ($request->hasFile('thumbnail')) {
            // Supprimer l'ancienne image si elle existe
            if ($course->thumbnail) {
                \Storage::disk('public')->delete($course->thumbnail);
            }
            $validated['thumbnail'] = $request->file('thumbnail')
                ->store('thumbnails', 'public');
        }

        $course->update($validated);

        return redirect()
            ->route('courses.show', $course)
            ->with('success', 'Le cours "' . $course->title . '" a été mis à jour avec succès !');
    }

    // ─────────────────────────────────────────────────────
    // destroy() — Suppression
    // ─────────────────────────────────────────────────────
    public function destroy(Course $course)
    {
        $this->authorize('delete', $course);

        // Supprimer l'image associée
        if ($course->thumbnail) {
            \Storage::disk('public')->delete($course->thumbnail);
        }

        $course->delete();

        return redirect()
            ->route('courses.index')
            ->with('success', 'Le cours "' . $course->title . '" a été supprimé.');
    }

    // ─────────────────────────────────────────────────────
    // Méthode privée — vérifie le rôle de l'utilisateur
    // ─────────────────────────────────────────────────────
    private function checkRole(array $roles): void
    {
        if (! in_array(auth()->user()->role, $roles)) {
            abort(403, 'Accès non autorisé.');
        }
    }
}