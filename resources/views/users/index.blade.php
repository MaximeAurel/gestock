@extends('layouts.app')

@section('content')
<div class="pagetitle fade-slide-up">
    <h1 class="fw-bold text-primary">Utilisateurs</h1>
    <p class="text-muted mb-0">Visibles uniquement par l’admin.</p>
</div>

<section class="section fade-slide-up">
    <div class="card card-hover-lift">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="card-title mb-0">Tous les utilisateurs</h5>
                <span class="badge bg-light text-dark">Admin seulement</span>
            </div>
            <table class="table table-hover align-middle" id="usersTable">
                <thead class="table-light">
                    <tr>
                        <th>Nom</th>
                        <th>Email</th>
                        <th>Rôle</th>
                        <th class="text-end">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $user)
                    <tr>
                        <td class="fw-semibold">{{ $user->nom }}</td>
                        <td>{{ $user->email }}</td>
                        <td>{{ $user->role->nom ?? '—' }}</td>
                        <td class="text-end">
                            @if($user->role_id === $adminRoleId)
                                <span class="badge bg-light text-muted">Même rôle (non modifiable)</span>
                            @else
                                <form action="{{ route('users.updateRole', $user->id) }}" method="POST" class="d-flex justify-content-end gap-2">
                                    @csrf
                                    @method('PATCH')
                                    <select name="role_id" class="form-select form-select-sm w-auto">
                                        @foreach($roles as $role)
                                            <option value="{{ $role->id }}" @selected($user->role_id == $role->id)>{{ $role->nom }}</option>
                                        @endforeach
                                    </select>
                                    <button type="submit" class="btn btn-outline-primary btn-sm">Changer</button>
                                </form>
                            @endif
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
    $('#usersTable').DataTable({ pageLength: 10 });
});
</script>
@endsection
