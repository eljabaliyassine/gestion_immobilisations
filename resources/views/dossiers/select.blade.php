@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header pb-0">
                    <h5>Sélection d'un dossier</h5>
                </div>
                <div class="card-body">
                    @if(session('error'))
                        <div class="alert alert-danger">
                            {{ session('error') }}
                        </div>
                    @endif
                    
                    @if(session('warning'))
                        <div class="alert alert-warning">
                            {{ session('warning') }}
                        </div>
                    @endif
                    
                    @if(session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif
                    
                    <p class="text-muted">Veuillez sélectionner un dossier pour continuer.</p>
                    
                    <form action="{{ route('dossiers.storeSelection') }}" method="POST">
                        @csrf
                        
                        <div class="form-group">
                            <label for="dossier_id" class="form-control-label">Dossier</label>
                            <select class="form-control" id="dossier_id" name="dossier_id" required>
                                <option value="">Sélectionner un dossier</option>
                                @foreach($dossiers as $dossier)
                                    <option value="{{ $dossier->id }}">
                                        {{ $dossier->code }} - {{ $dossier->nom }} 
                                        ({{ $dossier->client->nom ?? 'Client inconnu' }} / {{ $dossier->societe->nom ?? 'Société inconnue' }})
                                    </option>
                                @endforeach
                            </select>
                            @error('dossier_id')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="d-flex justify-content-end mt-4">
                            <button type="submit" class="btn btn-primary">Sélectionner</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
