<!-- ===================================================== -->
<!-- SIDEBAR -->
<!-- ===================================================== -->

@php
    // =====================================================
    // Récupération du rôle de l'utilisateur connecté
    // Cela évite de répéter Auth::user() partout
    // =====================================================
    $role = strtolower(trim(Auth::user()?->role?->nom ?? ''));
    $isAdmin = in_array($role, ['admin', 'administrateur'], true);
    $isComptable = in_array($role, ['comptable', 'gestionnaire stock', 'gestionnaire de stock'], true);
    $isVendeur = in_array($role, ['vendeur', 'commercial'], true);
@endphp

<aside id="sidebar" class="sidebar">

<ul class="sidebar-nav" id="sidebar-nav">


    <!-- ===================================================== -->
    <!-- DASHBOARD -->
    <!-- Visible pour tous les rôles
        ===================================================== -->
    <li class="nav-item">
        <a class="nav-link" href="{{ route('dashboard') }}">
            <i class="bi bi-grid"></i>
            <span>Dashboard</span>
        </a>
    </li>



    <!-- ===================================================== -->
    <!-- PRODUITS -->
    <!-- Visible seulement pour : Admin et Comptable
        ===================================================== -->
    @if($isAdmin || $isComptable)
    <li class="nav-item">

        <a class="nav-link collapsed"
           data-bs-target="#produits-nav"
           data-bs-toggle="collapse"
           href="#">

            <i class="bi bi-box-seam"></i>
            <span>Produits</span>

            <i class="bi bi-chevron-down ms-auto"></i>
        </a>

        <ul id="produits-nav"
            class="nav-content collapse"
            data-bs-parent="#sidebar-nav">

            <li>
                <a href="{{ route('produits.index') }}">
                    <i class="bi bi-circle"></i>
                    <span>Produits</span>
                </a>
            </li>

            <li>
                <a href="{{ route('categories.index') }}">
                    <i class="bi bi-circle"></i>
                    <span>Catégories</span>
                </a>
            </li>

            <li>
                <a href="{{ route('unites.index') }}">
                    <i class="bi bi-circle"></i>
                    <span>Unités</span>
                </a>
            </li>

            <li>
                <a href="{{ route('stocks.index') }}">
                    <i class="bi bi-circle"></i>
                    <span>Stocks</span>
                </a>
            </li>

            <li>
                <a href="{{ route('mouvement-stocks.index') }}">
                    <i class="bi bi-circle"></i>
                    <span>Mouvements de stock</span>
                </a>
            </li>

        </ul>

    </li>
    @endif



    <!-- ===================================================== -->
    <!-- ACHATS -->
    <!-- Visible pour : Admin et Comptable
        ===================================================== -->
    @if($isAdmin || $isComptable)
    <li class="nav-item">

        <a class="nav-link collapsed"
           data-bs-target="#achats-nav"
           data-bs-toggle="collapse"
           href="#">

            <i class="bi bi-cart-plus"></i>
            <span>Achats</span>

            <i class="bi bi-chevron-down ms-auto"></i>
        </a>

        <ul id="achats-nav"
            class="nav-content collapse"
            data-bs-parent="#sidebar-nav">

            <li>
                <a href="{{ route('achats.index') }}">
                    <i class="bi bi-circle"></i>
                    <span>Achats</span>
                </a>
            </li>

            <li>
                <a href="{{ route('fournisseurs.index') }}">
                    <i class="bi bi-circle"></i>
                    <span>Fournisseurs</span>
                </a>
            </li>

        </ul>

    </li>
    @endif



    <!-- ===================================================== -->
    <!-- VENTES -->
    <!-- Visible pour : Admin, Comptable et Vendeur
        ===================================================== -->
    @if($isAdmin || $isComptable || $isVendeur)
    <li class="nav-item">

        <a class="nav-link collapsed"
           data-bs-target="#ventes-nav"
           data-bs-toggle="collapse"
           href="#">

            <i class="bi bi-cash-stack"></i>
            <span>Ventes</span>

            <i class="bi bi-chevron-down ms-auto"></i>
        </a>

        <ul id="ventes-nav"
            class="nav-content collapse"
            data-bs-parent="#sidebar-nav">

            <li>
                <a href="{{ route('factures.index') }}">
                    <i class="bi bi-circle"></i>
                    <span>Factures</span>
                </a>
            </li>

            <li>
                <a href="{{ route('devis.index') }}">
                    <i class="bi bi-circle"></i>
                    <span>Devis</span>
                </a>
            </li>

            <li>
                <a href="{{ route('avoirs.index') }}">
                    <i class="bi bi-circle"></i>
                    <span>Avoirs</span>
                </a>
            </li>

            <li>
                <a href="{{ route('paiements.index') }}">
                    <i class="bi bi-circle"></i>
                    <span>Paiements</span>
                </a>
            </li>

        </ul>

    </li>
    @endif



    <!-- ===================================================== -->
    <!-- CLIENTS -->
    <!-- Visible pour tous les rôles
    ===================================================== -->
    <li class="nav-item">
        <a class="nav-link collapsed" href="{{ route('clients.index') }}">
            <i class="bi bi-people"></i>
            <span>Clients</span>
        </a>
    </li>



    <!-- ===================================================== -->
    <!-- UTILISATEURS -->
    <!-- Visible seulement pour Admin
        ===================================================== -->
    @if($isAdmin)
    <li class="nav-item">
        <a class="nav-link collapsed" href="{{ route('users.index') }}">
            <i class="bi bi-person"></i>
            <span>Utilisateurs</span>
        </a>
    </li>
    @endif



    <!-- ===================================================== -->
    <!-- RAPPORTS -->
    <!-- Visible pour : Admin et Comptable
        ===================================================== -->
    @if($isAdmin || $isComptable)
    <li class="nav-item">
        <a class="nav-link collapsed" href="{{ route('rapports.index') }}">
            <i class="bi bi-bar-chart"></i>
            <span>Rapports</span>
        </a>
    </li>
    @endif



    <!-- ===================================================== -->
    <!-- PARAMETRES -->
    <!-- Visible seulement pour Admin
        ===================================================== -->
    @if($isAdmin)
    <li class="nav-item">
        <a class="nav-link collapsed" href="{{ route('parametres.index') }}">
            <i class="bi bi-gear"></i>
            <span>Paramètres</span>
        </a>
    </li>
    @endif

    <!-- ===================================================== -->
    <!-- DECONNEXION -->
    <!-- Utilise POST pour respecter la sécurité Laravel -->
    <!-- ===================================================== -->
    <li class="nav-item">

        <form action="{{ route('logout') }}" method="POST">
            @csrf

            <button type="submit" class="nav-link collapsed border-0 bg-transparent w-100 text-start">
                <i class="bi bi-box-arrow-right"></i>
                <span>Déconnexion</span>
            </button>
        </form>

    </li>


</ul>

</aside>


