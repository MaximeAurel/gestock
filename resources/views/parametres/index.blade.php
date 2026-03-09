@extends('layouts.app')

@section('content')
<div class="pagetitle">
    <h1>Parametres</h1>
</div>

<section class="section">
    <div class="card">
        <div class="card-body pt-3">
            <h5 class="card-title">Parametres generaux</h5>

            <form action="{{ route('parametres.update') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="row mb-3">
                    <label class="col-sm-3 col-form-label">Nom entreprise</label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control" name="nom_entreprise" value="{{ old('nom_entreprise', $parametres['nom_entreprise'] ?? 'Gestock') }}" required>
                    </div>
                </div>

                <div class="row mb-3">
                    <label class="col-sm-3 col-form-label">Devise</label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control" name="devise" value="{{ old('devise', $parametres['devise'] ?? 'XAF') }}" required>
                    </div>
                </div>

                <div class="row mb-3">
                    <label class="col-sm-3 col-form-label">TVA (%)</label>
                    <div class="col-sm-9">
                        <input type="number" step="0.01" class="form-control" name="tva" value="{{ old('tva', $parametres['tva'] ?? 18) }}" required>
                    </div>
                </div>

                <div class="row mb-3">
                    <label class="col-sm-3 col-form-label">Logo</label>
                    <div class="col-sm-9">
                        <input type="file" class="form-control" name="logo" accept="image/png,image/jpeg">
                    </div>
                </div>

                <div class="text-end">
                    <button type="submit" class="btn btn-primary">Enregistrer</button>
                </div>
            </form>
        </div>
    </div>
</section>
@endsection
