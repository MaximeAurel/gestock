<!DOCTYPE html>

<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Connexion - Gestock</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">


{{-- Bootstrap --}}
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

{{-- Icons --}}
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

{{-- SweetAlert --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<style>
    body{
        background:#f4f6f9;
    }

    .auth-left{
        min-height:100vh;
        display:flex;
        align-items:center;
        justify-content:center;
        background:white;
    }

    .auth-card{
        width:100%;
        max-width:420px;
    }

    .form-control{
        height:48px;
        border-radius:10px;
    }

    .form-control:focus{
        border-color:#6366f1;
        box-shadow:0 0 0 .2rem rgba(99,102,241,.15);
    }

    .btn-primary{
        background:#6366f1;
        border:none;
        height:48px;
        border-radius:10px;
        font-weight:600;
    }

    .btn-primary:hover{
        background:#4f46e5;
    }

    .divider{
        display:flex;
        align-items:center;
        text-align:center;
        color:#6c757d;
        font-size:14px;
    }

    .divider::before,
    .divider::after{
        content:'';
        flex:1;
        border-bottom:1px solid #dee2e6;
    }

    .divider:not(:empty)::before{margin-right:.75em;}
    .divider:not(:empty)::after{margin-left:.75em;}

    .auth-right{
        min-height:100vh;
        background:linear-gradient(135deg,#6366f1,#4f46e5);
        color:white;
        display:flex;
        align-items:center;
        justify-content:center;
        text-align:center;
        padding:40px;
    }

    .toggle-password{
        position:absolute;
        top:50%;
        right:15px;
        transform:translateY(-50%);
        cursor:pointer;
        color:#6c757d;
    }

    @media(max-width:992px){
        .auth-right{display:none;}
    }
</style>

</head>
<body>

<div class="container-fluid">
    <div class="row">

    {{-- LEFT FORM --}}
    <div class="col-lg-6 auth-left">
        <div class="auth-card">

            <h3 class="fw-bold mb-1">Connexion</h3>
            <p class="text-muted mb-4">Accédez à votre espace Gestock</p>

            <form method="POST" action="{{ url('/login') }}">
                @csrf

                <div class="mb-3">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control" required>
                </div>

                <div class="mb-3 position-relative">
                    <label class="form-label">Mot de passe</label>
                    <input type="password" name="password" id="password" class="form-control" required>
                    <i class="bi bi-eye toggle-password" onclick="togglePassword()"></i>
                </div>

                <div class="d-flex justify-content-between mb-4">
                    <div>
                        <input type="checkbox" name="remember"> Se souvenir
                    </div>
                    <a href="#" class="text-decoration-none">Mot de passe oublié ?</a>
                </div>

                <button class="btn btn-primary w-100 mb-3">Se connecter</button>

            </form>

        </div>
    </div>

    {{-- RIGHT PANEL --}}
    <div class="col-lg-6 auth-right">
        <div>
            <h1 class="fw-bold">GESTOCK</h1>
            <p class="opacity-75">
                Application de gestion de stock et facturation professionnelle
            </p>
        </div>
    </div>

</div>


</div>

{{-- SweetAlert messages --}}
@if(session('success'))

<script>
Swal.fire({
    icon:'success',
    title:'Succès',
    text:'{{ session('success') }}',
    confirmButtonColor:'#6366f1'
});
</script>

@endif

@if(session('error'))

<script>
Swal.fire({
    icon:'error',
    title:'Erreur',
    text:'{{ session('error') }}',
    confirmButtonColor:'#6366f1'
});
</script>

@endif

<script>
function togglePassword(){
    const input=document.getElementById('password');
    input.type=input.type==='password'?'text':'password';
}
</script>

</body>
</html>
