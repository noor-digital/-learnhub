<section class="space-y-4">
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            Protection du compte et de l'historique
        </h2>
        <p class="mt-1 text-sm text-gray-600">
            La suppression définitive depuis cette page est désactivée afin de préserver
            les cours, inscriptions, avis, résultats de quiz et certificats associés au compte.
        </p>
    </header>

    @if (session('status') === 'account-deletion-protected')
        <div class="rounded-md bg-amber-50 p-4 text-sm text-amber-800" role="status">
            La suppression du compte est protégée. Contactez l'administration de LearnHub
            pour examiner une demande de fermeture sans effacer l'historique pédagogique.
        </div>
    @endif

    <div class="rounded-md border border-amber-200 bg-amber-50 p-4 text-sm text-amber-900">
        Vos données pédagogiques sont conservées. Pour toute demande de fermeture,
        adressez-vous à l'administration afin qu'elle vérifie les inscriptions et les
        contenus liés avant toute décision.
    </div>
</section>
