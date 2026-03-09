<header id="header" class="header fixed-top d-flex align-items-center">

  @php
      $user = Auth::user();
      $roleName = $headerRoleLabel ?? ($user?->role?->nom ?? 'Utilisateur');
      $role = strtolower(trim($user?->role?->nom ?? ''));
      $isAdmin = in_array($role, ['admin', 'administrateur'], true);
      $notifications = $headerNotifications ?? [];
      $notificationsCount = count($notifications);
      $profileRoute = route('users.show', $user?->id ?? 0);
      $settingsRoute = $isAdmin ? route('parametres.index') : route('dashboard');
  @endphp

  <div class="d-flex align-items-center justify-content-between">
    <a href="{{ route('dashboard') }}" class="logo d-flex align-items-center">
      <img src="{{ asset('assets/img/logo.png') }}" alt="">
      <span class="d-none d-lg-block">Gestock</span>
    </a>
    <i class="bi bi-list toggle-sidebar-btn"></i>
  </div>

  <div class="search-bar">
    <form class="search-form d-flex align-items-center" method="GET" action="{{ route('dashboard') }}">
      <input type="text" name="query" placeholder="Rechercher" title="Entrer un mot-clé">
      <button type="submit" title="Rechercher"><i class="bi bi-search"></i></button>
    </form>
  </div>

  <nav class="header-nav ms-auto">
    <ul class="d-flex align-items-center">

      <li class="nav-item d-block d-lg-none">
        <a class="nav-link nav-icon search-bar-toggle" href="#">
          <i class="bi bi-search"></i>
        </a>
      </li>

      <li class="nav-item dropdown">
        <a class="nav-link nav-icon" href="#" data-bs-toggle="dropdown" aria-expanded="false">
          <i class="bi bi-bell"></i>
          @if($notificationsCount > 0)
          <span class="badge bg-primary badge-number">{{ $notificationsCount > 9 ? '9+' : $notificationsCount }}</span>
          @endif
        </a>

        <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow notifications">
          <li class="dropdown-header">
            @if($notificationsCount > 0)
              {{ $notificationsCount }} notification(s) importante(s)
            @else
              Aucune notification importante
            @endif
          </li>

          <li><hr class="dropdown-divider"></li>

          @forelse($notifications as $notification)
            <li class="notification-item">
              <a href="{{ route($notification['route']) }}" class="d-flex align-items-start text-decoration-none text-dark w-100">
                <i class="bi {{ $notification['icon'] }} {{ $notification['iconClass'] }} me-2 mt-1"></i>
                <div>
                  <h4 class="mb-1">{{ $notification['title'] }}</h4>
                  <p class="mb-0">{{ $notification['message'] }}</p>
                  <p class="mb-0 text-muted">{{ $notification['time'] }}</p>
                </div>
              </a>
            </li>
            <li><hr class="dropdown-divider"></li>
          @empty
            <li class="notification-item">
              <i class="bi bi-check-circle text-success"></i>
              <div>
                <h4>Rien d'urgent</h4>
                <p>Tout est a jour pour votre role.</p>
                <p>Aujourd'hui</p>
              </div>
            </li>
            <li><hr class="dropdown-divider"></li>
          @endforelse
        </ul>
      </li>

      <li class="nav-item dropdown pe-3">
        <a class="nav-link nav-profile d-flex align-items-center pe-0" href="#" data-bs-toggle="dropdown" aria-expanded="false">
          <img src="{{ asset('assets/img/profile-img.jpg') }}" alt="Profile" class="rounded-circle">
          <span class="d-none d-md-block dropdown-toggle ps-2">{{ $user?->nom ?? 'Utilisateur' }}</span>
        </a>

        <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow profile">
          <li class="dropdown-header">
            <h6>{{ $user?->nom ?? 'Utilisateur' }}</h6>
            <span>{{ $roleName }}</span>
          </li>

          <li><hr class="dropdown-divider"></li>

          <li>
            <a class="dropdown-item d-flex align-items-center" href="{{ $profileRoute }}">
              <i class="bi bi-person"></i>
              <span>Mon profil</span>
            </a>
          </li>

          <li><hr class="dropdown-divider"></li>

          <li>
            <a class="dropdown-item d-flex align-items-center" href="{{ $settingsRoute }}">
              <i class="bi bi-gear"></i>
              <span>Parametres du compte</span>
            </a>
          </li>

          <li><hr class="dropdown-divider"></li>

          <li>
            <form action="{{ route('logout') }}" method="POST" class="m-0">
              @csrf
              <button type="submit" class="dropdown-item d-flex align-items-center border-0 bg-transparent w-100">
                <i class="bi bi-box-arrow-right"></i>
                <span>Deconnexion</span>
              </button>
            </form>
          </li>
        </ul>
      </li>

    </ul>
  </nav>

</header>
