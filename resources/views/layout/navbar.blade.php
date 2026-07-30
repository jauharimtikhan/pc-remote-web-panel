<header class="header">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-5 col-md-5 col-6">
                <div class="header-left d-flex align-items-center">
                    <div class="menu-toggle-btn mr-15">
                        <button id="menu-toggle" class="main-btn primary-btn btn-hover">
                            <i class="lni lni-chevron-left me-2"></i> Menu
                        </button>
                    </div>
                </div>
            </div>
            <div class="col-lg-7 col-md-7 col-6">
                <div class="header-right">

                    <!-- profile start -->
                    <div class="profile-box ml-15">
                        <button class="dropdown-toggle bg-transparent border-0" type="button" id="profile"
                            data-bs-toggle="dropdown" aria-expanded="false">
                            <div class="profile-info">
                                <div class="info">
                                    <div class="image">
                                        <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()?->name ?? 'PM') }}"
                                            alt="avatar image pramaxx remote" />
                                    </div>
                                    <div>
                                        <h6 class="fw-500">{{ explode(' ', Auth::user()?->name)[0] ?? 'Admin Pramaxx' }}
                                        </h6>
                                        <p>{{ Auth::user()?->role ?? 'user' }}</p>
                                    </div>
                                </div>
                            </div>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="profile">
                            <li>
                                <div class="author-info flex items-center !p-1">
                                    <div class="image">
                                        <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()?->name ?? 'PM') }}"
                                            alt="avatar image pramaxx remote" />
                                    </div>
                                    <div class="content">
                                        <h4 class="text-sm">
                                            {{ explode(' ', Auth::user()?->name)[0] ?? 'Admin Pramaxx' }}</h4>
                                        <a class="text-black/40 dark:text-white/40 hover:text-black dark:hover:text-white text-xs"
                                            href="#">{{ Auth::user()?->role ?? 'user' }}</a>
                                    </div>
                                </div>
                            </li>
                            <li class="divider"></li>
                            <li>
                                <a href="{{ route('admin.auth.logout') }}"> <i class="lni lni-exit"></i> Logout </a>
                            </li>
                        </ul>
                    </div>
                    <!-- profile end -->
                </div>
            </div>
        </div>
    </div>
</header>
