@extends('layouts.app')

@section('content')
<h1>Liste des produits</h1>

<a href="{{ route('produits.create') }}" class="btn btn-success mb-3">Ajouter un produit</a>

<table id="produitsTable" class="table table-striped table-bordered">
    <thead>
        <tr>
            <th>Nom</th>
            <th>Catégorie</th>
            <th>Unité</th>
            <th>Stock</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        @foreach($produits as $produit)
            <tr>
                <td>{{ $produit->nom }}</td>
                <td>{{ $produit->categorie->nom ?? '' }}</td>
                <td>{{ $produit->unite->nom ?? '' }}</td>
                <td>{{ $produit->stock->quantite ?? 0 }}</td>
                <td>
                    <a href="{{ route('produits.edit', $produit->id) }}" class="btn btn-primary btn-sm">Éditer</a>
                    <button class="btn btn-danger btn-sm btn-delete" data-id="{{ $produit->id }}">Supprimer</button>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>

@endsection

@section('scripts')
<script>
$(document).ready(function() {
    // Initialiser DataTables
    $('#produitsTable').DataTable();

    // SweetAlert pour la suppression
    $('.btn-delete').click(function() {
        let id = $(this).data('id');
        Swal.fire({
            title: 'Êtes-vous sûr ?',
            text: "Vous ne pourrez pas revenir en arrière !",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Oui, supprimer !'
        }).then((result) => {
            if (result.isConfirmed) {
                // Créer et soumettre un formulaire pour DELETE
                let form = $('<form>', {
                    'method': 'POST',
                    'action': '/produits/' + id
                }).append('@csrf').append('@method("DELETE")');
                $('body').append(form);
                form.submit();
            }
        })
    });
});
</script>
@endsection