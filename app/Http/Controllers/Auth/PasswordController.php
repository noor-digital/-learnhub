<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Password;

class PasswordController extends Controller
{
    /**
     * PUT /password
     * Met à jour le mot de passe de l'utilisateur connecté (depuis le profil).
     *
     * Les erreurs sont placées dans le "bag" `updatePassword`, lu par
     * resources/views/profile/partials/update-password-form.blade.php.
     */
    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validateWithBag('updatePassword', [
            'current_password' => ['required', 'current_password'],
            'password'         => ['required', 'confirmed', 'different:current_password', Password::defaults()],
        ], [
            'current_password.required'         => 'Veuillez saisir votre mot de passe actuel.',
            'current_password.current_password' => 'Le mot de passe actuel est incorrect.',
            'password.required'                 => 'Veuillez saisir un nouveau mot de passe.',
            'password.confirmed'                => 'La confirmation ne correspond pas au nouveau mot de passe.',
            'password.different'                => 'Le nouveau mot de passe doit être différent du mot de passe actuel.',
            // Règles de complexité (Password::defaults(), voir AppServiceProvider)
            'password.min'               => 'Le mot de passe doit contenir au moins :min caractères.',
            'password.password.mixed'           => 'Le mot de passe doit contenir au moins une majuscule et une minuscule.',
            'password.password.letters'         => 'Le mot de passe doit contenir au moins une lettre.',
            'password.password.numbers'         => 'Le mot de passe doit contenir au moins un chiffre.',
        ]);

        // Le cast "hashed" du modèle User ne re-hashe pas une valeur déjà hashée.
        // On renouvelle aussi le remember_token : les anciens cookies
        // "se souvenir de moi" ne sont plus valides après le changement.
        $request->user()->forceFill([
            'password'       => Hash::make($validated['password']),
            'remember_token' => Str::random(60),
        ])->save();

        return back()->with('status', 'password-updated');
    }
}
