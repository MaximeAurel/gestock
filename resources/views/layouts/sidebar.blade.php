<!-- ===================================================== -->
<!-- SIDEBAR -->
<!-- ===================================================== -->
<aside id="sidebar" class="sidebar">

<ul class="sidebar-nav" id="sidebar-nav">


    <!-- ===================================================== -->
    <!-- DASHBOARD -->
    <!-- ===================================================== -->
    <li class="nav-item">
        <a class="nav-link" href="{{ route('dashboard') }}">
            <i class="bi bi-grid"></i>
            <span>Dashboard</span>
        </a>
    </li>


    <!-- ===================================================== -->
    <!-- PRODUITS -->
    <!-- Gestion des articles et du stock -->
    <!-- ===================================================== -->
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



    <!-- ===================================================== -->
    <!-- ACHATS -->
    <!-- Gestion des approvisionnements -->
    <!-- ===================================================== -->
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



    <!-- ===================================================== -->
    <!-- VENTES -->
    <!-- Gestion commerciale -->
    <!-- ===================================================== -->
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



    <!-- ===================================================== -->
    <!-- CLIENTS -->
    <!-- Gestion de la clientèle -->
    <!-- ===================================================== -->
    <li class="nav-item">
        <a class="nav-link collapsed" href="{{ route('clients.index') }}">
            <i class="bi bi-people"></i>
            <span>Clients</span>
        </a>
    </li>



    <!-- ===================================================== -->
    <!-- UTILISATEURS -->
    <!-- Gestion des comptes utilisateurs -->
    <!-- ===================================================== -->
    <li class="nav-item">
        <a class="nav-link collapsed" href="{{ route('users.index') }}">
            <i class="bi bi-person"></i>
            <span>Utilisateurs</span>
        </a>
    </li>



    <!-- ===================================================== -->
    <!-- RAPPORTS -->
    <!-- Statistiques -->
    <!-- ===================================================== -->
    <li class="nav-item">
        <a class="nav-link collapsed" href="{{ route('rapports.index') }}">
            <i class="bi bi-bar-chart"></i>
            <span>Rapports</span>
        </a>
    </li>



    <!-- ===================================================== -->
    <!-- PARAMETRES -->
    <!-- Configuration de l'application -->
    <!-- ===================================================== -->
    <li class="nav-item">
        <a class="nav-link collapsed" href="{{ route('parametres.index') }}">
            <i class="bi bi-gear"></i>
            <span>Paramètres</span>
        </a>
    </li>


</ul>

</aside>