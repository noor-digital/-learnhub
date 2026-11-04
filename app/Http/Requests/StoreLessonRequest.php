<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreLessonRequest extends FormRequest
{
    public function authorize(): bool
    {
        // L'autorisation fine est gérée dans le controller via la Policy
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'title'     => ['required', 'string', 'max:255'],
            'video_url' => ['nullable', 'url', 'max:500'],
            'duration'  => ['nullable', 'integer', 'min:0', 'max:999'],
            'order'     => ['nullable', 'integer', 'min:1'],
        ];
    }

    public function messages(): array
    {
        return [
            'title.required'  => 'Le titre de la leçon est obligatoire.',
            'video_url.url'   => 'L\'URL de la vidéo n\'est pas valide.',
            'duration.integer' => 'La durée doit être un nombre entier.',
        ];
    }
}