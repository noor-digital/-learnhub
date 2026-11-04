<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\View\View;

class PasswordResetLinkController extends Controller
{
    /**
     * GET /forgot-password
     * Affiche le formulaire de demande de réinitialisation.
     */
    public function create(): View
    {
        return view('auth.forgot-password');
    }

    /**
     * POST /forgot-password
     * Envoie le lien de réinitialisation par email.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => ['required', 'email'],
        ], [
            'email.required' => "L'adresse email est obligatoire.",
            'email.email'    => "L'adresse email n'est pas valide.",
        ]);

        // Laravel Password Broker : génère le jeton (stocké hashé, expiration
        // et délai anti-spam définis dans config/auth.php) et envoie l'email
        // via le mailer configuré.
        Password::sendResetLink($request->only('email'));

        /*
         * SÉCURITÉ — anti-énumération de comptes :
         * le statut du broker (lien envoyé / adresse inconnue / trop de
         * demandes) est volontairement ignoré. La réponse est identique dans
         * tous les cas, afin de ne jamais révéler si une adresse possède un
         * compte.
         */
        return back()->with(
            'status',
            "Si un compte existe pour cette adresse, un lien de réinitialisation vient d'y être envoyé."
        );
    }
}
