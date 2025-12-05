<?php

$archivoActual = basename($_SERVER["PHP_SELF"]);

?>

<body>
    <!-- Layout wrapper -->
    <div class="layout-wrapper layout-content-navbar">
        <div class="layout-container">
            <!-- Menu -->

            <aside
                id="layout-menu"
                class="layout-menu menu-vertical menu bg-menu-theme">
                <div class="app-brand demo">
                    <a href="./composiciones.php" class="app-brand-link">
                        <span class="app-brand-logo demo">
                            <span class="text-primary">
                                <img
                                    src="./assets/img/logoSena.png"
                                    class="img-fluid w-75"
                                    alt="" />
                            </span>
                        </span>
                        <span class="app-brand-text demo menu-text fw-bold">Bingo</span>
                        <small class="text-success">Literario</small>
                    </a>

                    <a
                        href="javascript:void(0);"
                        class="layout-menu-toggle menu-link text-large ms-auto">
                        <i class="bx bx-chevron-left d-block d-xl-none align-middle"></i>
                    </a>
                </div>

                <div class="menu-divider mt-0"></div>

                <div class="menu-inner-shadow"></div>

                <ul class="menu-inner py-1">
                    <li class="menu-item">
                        <a href="./pages/index.html" class="menu-link">
                            <i class="menu-icon tf-icons bx bx-home-smile"></i>
                            <div class="text-truncate" data-i18n="Basic">Dashboard</div>
                        </a>
                    </li>

                    <li class="menu-item <?php echo ($archivoActual == "composiciones.php" ? "active" : "") ?>">
                        <a href="composiciones.php" class="menu-link">
                            <i class='menu-icon tf-icons bx bx-book-bookmark'></i>
                            <div class="text-truncate" data-i18n="Basic">Obras literarias</div>
                        </a>
                    </li>

                    <li class="menu-item <?php echo ($archivoActual == "sala.php" ? "active" : "") ?>">
                        <a href="sala.php" class="menu-link">
                            <i class="menu-icon tf-icons bx bx-collection"></i>
                            <div class="text-truncate" data-i18n="Basic">Salas</div>
                        </a>
                    </li>

                    <li class="menu-item <?php echo ($archivoActual == "balotas.php" ? "active" : "") ?>">
                        <a href="balotas.php" class="menu-link">
                            <i class='menu-icon tf-icons bx bx-tennis-ball'></i>
                            <div class="text-truncate" data-i18n="Basic">Balotas</div>
                        </a>
                    </li>


                    <!-- Authenticacion  -->
                    <li class="menu-item">
                        <a href="javascript:void(0);" class="menu-link menu-toggle">
                            <i class="menu-icon tf-icons bx bx-lock-open-alt"></i>
                            <div class="text-truncate" data-i18n="Authentications">
                                Authentications
                            </div>
                        </a>
                        <ul class="menu-sub">
                            <li class="menu-item">
                                <a
                                    href="./pages/auth-login-basic.html"
                                    class="menu-link"
                                    target="_blank">
                                    <div class="text-truncate" data-i18n="Basic">Login</div>
                                </a>
                            </li>
                            <li class="menu-item">
                                <a
                                    href="./pages/auth-register-basic.html"
                                    class="menu-link"
                                    target="_blank">
                                    <div class="text-truncate" data-i18n="Basic">Register</div>
                                </a>
                            </li>
                            <li class="menu-item">
                                <a
                                    href="./pages/auth-forgot-password-basic.html"
                                    class="menu-link"
                                    target="_blank">
                                    <div class="text-truncate" data-i18n="Basic">
                                        Forgot Password
                                    </div>
                                </a>
                            </li>
                        </ul>
                    </li>

                    <!-- Components -->
                    <li class="menu-header small text-uppercase">
                        <span class="menu-header-text">Components</span>
                    </li>
                    <!-- Cards -->
                    <li class="menu-item">
                        <a href="./pages/cards-basic.html" class="menu-link">
                            <i class="menu-icon tf-icons bx bx-collection"></i>
                            <div class="text-truncate" data-i18n="Basic">Cards</div>
                        </a>
                    </li>
                    <!-- User interface -->
                    <li class="menu-item">
                        <a href="javascript:void(0)" class="menu-link menu-toggle">
                            <i class="menu-icon tf-icons bx bx-box"></i>
                            <div class="text-truncate" data-i18n="User interface">
                                User interface
                            </div>
                        </a>
                        <ul class="menu-sub">
                            <li class="menu-item">
                                <a href="./pages/ui-accordion.html" class="menu-link">
                                    <div class="text-truncate" data-i18n="Accordion">
                                        Accordion
                                    </div>
                                </a>
                            </li>
                            <li class="menu-item">
                                <a href="./pages/ui-alerts.html" class="menu-link">
                                    <div class="text-truncate" data-i18n="Alerts">Alerts</div>
                                </a>
                            </li>
                            <li class="menu-item">
                                <a href="./pages/ui-badges.html" class="menu-link">
                                    <div class="text-truncate" data-i18n="Badges">Badges</div>
                                </a>
                            </li>
                            <li class="menu-item">
                                <a href="./pages/ui-buttons.html" class="menu-link">
                                    <div class="text-truncate" data-i18n="Buttons">Buttons</div>
                                </a>
                            </li>
                            <li class="menu-item">
                                <a href="./pages/ui-carousel.html" class="menu-link">
                                    <div class="text-truncate" data-i18n="Carousel">
                                        Carousel
                                    </div>
                                </a>
                            </li>
                            <li class="menu-item">
                                <a href="./pages/ui-collapse.html" class="menu-link">
                                    <div class="text-truncate" data-i18n="Collapse">
                                        Collapse
                                    </div>
                                </a>
                            </li>
                            <li class="menu-item">
                                <a href="./pages/ui-dropdowns.html" class="menu-link">
                                    <div class="text-truncate" data-i18n="Dropdowns">
                                        Dropdowns
                                    </div>
                                </a>
                            </li>
                            <li class="menu-item">
                                <a href="./pages/ui-footer.html" class="menu-link">
                                    <div class="text-truncate" data-i18n="Footer">Footer</div>
                                </a>
                            </li>
                            <li class="menu-item">
                                <a href="./pages/ui-list-groups.html" class="menu-link">
                                    <div class="text-truncate" data-i18n="List Groups">
                                        List groups
                                    </div>
                                </a>
                            </li>
                            <li class="menu-item">
                                <a href="./pages/ui-modals.html" class="menu-link">
                                    <div class="text-truncate" data-i18n="Modals">Modals</div>
                                </a>
                            </li>
                            <li class="menu-item">
                                <a href="./pages/ui-navbar.html" class="menu-link">
                                    <div class="text-truncate" data-i18n="Navbar">Navbar</div>
                                </a>
                            </li>
                            <li class="menu-item">
                                <a href="./pages/ui-offcanvas.html" class="menu-link">
                                    <div class="text-truncate" data-i18n="Offcanvas">
                                        Offcanvas
                                    </div>
                                </a>
                            </li>
                            <li class="menu-item">
                                <a href="./pages/ui-pagination-breadcrumbs.html" class="menu-link">
                                    <div
                                        class="text-truncate"
                                        data-i18n="Pagination & Breadcrumbs">
                                        Pagination &amp; Breadcrumbs
                                    </div>
                                </a>
                            </li>
                            <li class="menu-item">
                                <a href="./pages/ui-progress.html" class="menu-link">
                                    <div class="text-truncate" data-i18n="Progress">
                                        Progress
                                    </div>
                                </a>
                            </li>
                            <li class="menu-item">
                                <a href="./pages/ui-spinners.html" class="menu-link">
                                    <div class="text-truncate" data-i18n="Spinners">
                                        Spinners
                                    </div>
                                </a>
                            </li>
                            <li class="menu-item">
                                <a href="./pages/ui-tabs-pills.html" class="menu-link">
                                    <div class="text-truncate" data-i18n="Tabs & Pills">
                                        Tabs &amp; Pills
                                    </div>
                                </a>
                            </li>
                            <li class="menu-item">
                                <a href="./pages/ui-toasts.html" class="menu-link">
                                    <div class="text-truncate" data-i18n="Toasts">Toasts</div>
                                </a>
                            </li>
                            <li class="menu-item">
                                <a href="./pages/ui-tooltips-popovers.html" class="menu-link">
                                    <div class="text-truncate" data-i18n="Tooltips & Popovers">
                                        Tooltips &amp; Popovers
                                    </div>
                                </a>
                            </li>
                            <li class="menu-item">
                                <a href="./pages/ui-typography.html" class="menu-link">
                                    <div class="text-truncate" data-i18n="Typography">
                                        Typography
                                    </div>
                                </a>
                            </li>
                        </ul>
                    </li>

                    <li class="menu-item">
                        <a href="./pages/icons-boxicons.html" class="menu-link">
                            <i class="menu-icon tf-icons bx bx-crown"></i>
                            <div class="text-truncate" data-i18n="Boxicons">Boxicons</div>
                        </a>
                    </li>
                </ul>
            </aside>
            <!-- / Menu -->