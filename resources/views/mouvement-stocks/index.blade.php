@extends('layouts.app')

@section('content')
<div class="pagetitle fade-slide-up">
    <h1 class="fw-bold text-primary">Mouvements de stock</h1>
    <p class="text-muted mb-0">Journal des entrées et sorties.</p>
</div>

<section class="section fade-slide-up">
    <div class="card card-hover-lift">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <h5 class="card-title mb-0">Historique</h5>
                <span class="text-muted small">Tri du plus récent</span>
            </div>
            <table id="mvTable" class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Produit</th>
                        <th>Type</th>
                        <th>Quantité</th>
                        <th>Motif</th>
                        <th>Date</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($mouvements as $mv)
                    <tr>
                        <td class="fw-semibold">{{ $mv->produit->designation ?? '—' }}</td>
                        <td>
                            @if($mv->type === 'entree')
                                <span class="badge bg-success">Entrée</span>
                            @else
                                <span class="badge bg-danger">Sortie</span>
                            @endif
                        </td>
                        <td><span class="badge bg-light text-dark">{{ $mv->quantite }}</span></td>
                        <td>{{ $mv->motif ?? '—' }}</td>
                        <td>{{ $mv->created_at->format('d/m/Y H:i') }}</td>
                        <td class="text-end">
                            <a href="{{ route('mouvement-stocks.show', $mv->id) }}" class="btn btn-outline-primary btn-sm">
                                <i class="bi bi-eye"></i>
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</section>
@endsection

@section('scripts')
<script>
$(function() {
    $('#mvTable').DataTable({
        pageLength: 10,
        ordering: false
    });
});
</script>
@endsection
