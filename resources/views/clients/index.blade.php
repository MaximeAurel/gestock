@extends('layouts.app')

@section('content')
<div class="pagetitle fade-slide-up">
    <h1 class="fw-bold text-primary">Clients</h1>
    <p class="text-muted mb-0">Gestion des clients et coordonnées.</p>
</div>

<section class="section fade-slide-up">
    <div class="row g-3 mb-3">
        <div class="col-md-4">
            <div class="card card-hover-lift">
                <div class="card-body d-flex justify-content-between align-items-start">
                    <div>
                        <p class="text-muted mb-1">Total clients</p>
                        <h4 class="fw-bold">{{ $clients->count() }}</h4>
                    </div>
                    <span class="badge badge-soft rounded-pill">Contacts</span>
                </div>
            </div>
        </div>
        <div class="col-md-8 d-flex justify-content-end align-items-center gap-2">
            <button class="btn btn-gradient-primary" data-bs-toggle="modal" data-bs-target="#modalCreateClient">
                <i class="bi bi-person-plus me-2"></i>Nouveau client
            </button>
        </div>
    </div>

    <div class="card card-hover-lift">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <h5 class="card-title mb-0">Liste des clients</h5>
                <span class="text-muted small">Carnet d'adresses</span>
            </div>
            <table id="clientsTable" class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Nom</th>
                        <th>Email</th>
                        <th>Téléphone</th>
                        <th>Adresse</th>
                        <th>Ville</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($clients as $client)
                    <tr>
                        <td class="fw-semibold">{{ $client->nom }}</td>
                        <td>{{ $client->email ?? '-' }}</td>
                        <td>{{ $client->telephone ?? '-' }}</td>
                        <td>{{ $client->adresse ?? '-' }}</td>
                        <td>{{ $client->ville ?? '-' }}</td>
                        <td class="text-end">
                            <div class="btn-group">
                                <a href="{{ route('clients.edit', $client->id) }}" class="btn btn-outline-primary btn-sm">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form action="{{ route('clients.destroy', $client->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-outline-danger btn-sm" onclick="return confirm('Supprimer ce client ?');">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</section>

<!-- Modal creation client -->
<div class="modal fade" id="modalCreateClient" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content glow-border">
            <div class="modal-header">
                <h5 class="modal-title">Nouveau client</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('clients.store') }}" method="POST">
                @csrf
                <div class="modal-body row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Nom *</label>
                        <input type="text" name="nom" class="form-control" required placeholder="Raison sociale ou nom complet">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control" placeholder="exemple@domaine.com">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Téléphone</label>
                        <input type="text" name="telephone" class="form-control" placeholder="+241 ...">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Ville</label>
                        <input type="text" name="ville" class="form-control" placeholder="Libreville">
                    </div>
                    <div class="col-12">
                        <label class="form-label">Adresse</label>
                        <textarea name="adresse" class="form-control" rows="2" placeholder="Adresse complète"></textarea>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Pays</label>
                        <input type="text" name="pays" class="form-control" placeholder="Gabon">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-gradient-primary">Enregistrer</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
(function() {
    $('#clientsTable').DataTable({ pageLength: 10 });
})();
</script>
@endsection
