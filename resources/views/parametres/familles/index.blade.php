@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span>{{ __('Liste des familles') }}</span>
                    <a href="{{ route('parametres.familles.create') }}" class="btn btn-sm btn-primary">Ajouter une famille</a>
                </div>

                <div class="card-body">
                    @if (session('success'))
                        <div class="alert alert-success" role="alert">
                            {{ session('success') }}
                        </div>
                    @endif
                    
                    @if (session('error'))
                        <div class="alert alert-danger" role="alert">
                            {{ session('error') }}
                        </div>
                    @endif

                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Code</th>
                                    <th>Libellé</th>
                                    <th>Compte Immobilisation</th>
                                    <th>Compte Amortissement</th>
                                    <th>Compte Dotation</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($familles as $famille)
                                    <tr>
                                        <td>{{ $famille->code }}</td>
                                        <td>{{ $famille->libelle }}</td>
                                        <td>
                                            @if($famille->compteImmobilisation)
                                                {{ $famille->compteImmobilisation->numero }} - {{ $famille->compteImmobilisation->libelle }}
                                            @endif
                                        </td>
                                        <td>
                                            @if($famille->compteAmortissement)
                                                {{ $famille->compteAmortissement->numero }} - {{ $famille->compteAmortissement->libelle }}
                                            @endif
                                        </td>
                                        <td>
                                            @if($famille->compteDotation)
                                                {{ $famille->compteDotation->numero }} - {{ $famille->compteDotation->libelle }}
                                            @endif
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('parametres.familles.edit', $famille->id) }}" class="btn btn-sm btn-info">Modifier</a>
                                                <form action="{{ route('parametres.familles.destroy', $famille->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette famille ?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger">Supprimer</button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center">Aucune famille trouvée</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="d-flex justify-content-center mt-3">
                        {{ $familles->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
