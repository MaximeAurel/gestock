@extends('layouts.app')

@section('content')
<div class="pagetitle fade-slide-up">
    <h1 class="fw-bold text-primary">Modifier avoir</h1>
</div>

<section class="section fade-slide-up">
    <div class="card card-hover-lift">
        <div class="card-body">
            <form action="{{ route('avoirs.update', $avoir->id) }}" method="POST" class="row g-3">
                @csrf
                @method('PUT')
                <div class="col-md-4">
                    <label class="form-label">Numéro</label>
                    <input type="text" name="numero" class="form-control" value="{{ $avoir->numero }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Facture</label>
                    <select name="facture_id" class="form-select" required>
                        @foreach($factures as $f)
                        <option value="{{ $f->id }}" @selected($avoir->facture_id == $f->id)>{{ $f->numero }} - {{ $f->client->nom ?? '' }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Date</label>
                    <input type="date" name="date_avoir" class="form-control" value="{{ $avoir->date_avoir }}" required>
                </div>
                <div class="col-md-4">
                    @php
                        $role = strtolower(Auth::user()->role->nom ?? '');
                        $isAdmin = in_array($role, ['admin', 'administrateur'], true);
                    @endphp
                    <label class="form-label">Montant</label>
                    <input type="number" step="0.01" name="montant" class="form-control" value="{{ $avoir->montant }}"
                        @unless($isAdmin) readonly @endunless required>
                    @unless($isAdmin)
                    <small class="text-muted">Montant verrouillé (réservé à l'administrateur).</small>
                    @endunless
                </div>
                <div class="col-md-8">
                    <label class="form-label">Motif</label>
                    <input type="text" name="motif" class="form-control" value="{{ $avoir->motif }}">
                </div>
                <div class="col-12 d-flex justify-content-end gap-2">
                    <a href="{{ route('avoirs.index') }}" class="btn btn-outline-secondary">Retour</a>
                    <button type="submit" class="btn btn-gradient-primary">Mettre à jour</button>
                </div>
            </form>
        </div>
    </div>
</section>
@endsection
