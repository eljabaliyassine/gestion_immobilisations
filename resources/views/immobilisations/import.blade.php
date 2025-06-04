@extends("layouts.app")

@section("title", "Importer des Immobilisations")

@section("content")
<div class="container">
    <h1>Importer des Immobilisations</h1>

    <div class="card">
        <div class="card-header">Importer un fichier Excel ou CSV</div>
        <div class="card-body">
            @if (session("success"))
                <div class="alert alert-success">{{ session("success") }}</div>
            @endif
            @if (session("error"))
                <div class="alert alert-danger">{{ session("error") }}</div>
            @endif
            @if ($errors->any())
                <div class="alert alert-danger">
                    <strong>Erreurs lors de l'import :</strong>
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route("immobilisations.import.handle") }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="mb-3">
                    <label for="import_file" class="form-label">Sélectionner le fichier (Excel: .xlsx, .xls; CSV: .csv, .txt)</label>
                    <input class="form-control" type="file" id="import_file" name="import_file" required accept=".xlsx,.xls,.csv,.txt">
                    <div class="form-text">
                        Le fichier doit contenir les colonnes suivantes dans cet ordre : <br>
                        <code>code_barre</code>, <code>designation</code>, <code>description</code> (optionnel), <code>famille_code</code>, <code>statut</code>, <code>date_acquisition</code> (YYYY-MM-DD), <code>valeur_acquisition</code>, <code>fournisseur_code</code> (optionnel), <code>numero_facture</code> (optionnel), <code>site_code</code>, <code>service_code</code>, <code>emplacement</code> (optionnel).
                        <br>Les codes (famille, fournisseur, site, service) doivent correspondre aux codes existants dans les paramètres pour ce dossier.
                        <br>Pour les fichiers CSV/TXT, utiliser le point-virgule (;) comme séparateur.
                    </div>
                </div>

                {{-- Add options for import behavior if needed (e.g., update existing, skip errors) --}}

                <button type="submit" class="btn btn-primary">Lancer l'Import</button>
                <a href="{{ route("immobilisations.index") }}" class="btn btn-secondary">Annuler</a>
            </form>

            <hr>
            <h5>Télécharger un modèle</h5>
            <p>Vous pouvez télécharger un fichier modèle Excel pour vous aider à préparer vos données.</p>
            {{-- TODO: Add route/button to download template --}}
            <a href="#" class="btn btn-outline-secondary">Télécharger Modèle Excel</a>

        </div>
    </div>
</div>
@endsection
