<!-- BEGIN #header -->
<div id="header" class="app-header">
    <!-- BEGIN mobile-toggler -->
    <div class="mobile-toggler">
        <button type="button" class="menu-toggler" @if (!empty($appTopNav) && !empty($appSidebarHide)) data-toggle="top-nav-mobile" @else data-toggle="sidebar-mobile" @endif>
            <span class="bar"></span>
            <span class="bar"></span>
        </button>
    </div>
    <!-- END mobile-toggler -->

    <!-- BEGIN brand -->
    <div class="brand">
        <div class="desktop-toggler">
            <button type="button" class="menu-toggler" @if (empty($appSidebarHide))data-toggle="sidebar-minify"@endif>
                <span class="bar"></span>
                <span class="bar"></span>
            </button>
        </div>

        <a href="/" class="brand-logo">
            <img src="/assets/img/logo.png" class="invert-dark" alt="" height="20" />
        </a>
    </div>
    <!-- END brand -->

    <!-- BEGIN menu -->
    <div class="menu">
        <form class="menu-search" method="POST" name="header_search_form">
            <div class="menu-search-icon"><i class="fa fa-search"></i></div>
            <div class="menu-search-input">
                <input type="text" class="form-control" placeholder="Search menu..." />
            </div>
        </form>
     <div class="menu-item dropdown">
       
         <div class="">


   
    <div class="p-2 text-center mb-n1">
        
    </div>
</div>
</div>
        <div class="menu-item dropdown">
            <a href="#" data-bs-toggle="dropdown" data-display="static" class="menu-link">
              <div class="menu-img online">
    @if(Auth::user()->profile_image)
        <img src="{{ Auth::user()->profile_image_url }}" 
             style="width:30px; height:30px; border-radius:50%; object-fit:cover;">
    @else
        @php
            $nameParts = explode(' ', trim(Auth::user()->name));
            $initials = strtoupper(substr($nameParts[0], 0, 1) . (isset($nameParts[1]) ? substr($nameParts[1], 0, 1) : ''));
        @endphp
        <div style="width:30px; height:30px; border-radius:50%; background:#ddd; 
                    display:flex; align-items:center; justify-content:center; 
                    font-weight:bold; color:#555; font-size:12px;">
            {{ $initials }}
        </div>
    @endif
</div>
               <div class="menu-text">{{ Auth::user()->name }}</div>
            </a>
            <div class="dropdown-menu dropdown-menu-end me-lg-3">
                <a class="dropdown-item d-flex align-items-center" href="/profile/edit">Edit Profile <i class="fa fa-user-circle fa-fw ms-auto text-body text-opacity-50"></i></a>
                <a class="dropdown-item d-flex align-items-center" href="/profile">Setting <i class="fa fa-wrench fa-fw ms-auto text-body text-opacity-50"></i></a>
                <div class="dropdown-divider"></div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                <a class="dropdown-item d-flex align-items-center" href="route('logout')"
                                    onclick="event.preventDefault();
                                                this.closest('form').submit();">Log Out <i class="fa fa-toggle-off fa-fw ms-auto text-body text-opacity-50"></i></a>
             </form>            </div>
        </div>
    </div>
    <!-- END menu -->
</div>
<!-- END #header -->
