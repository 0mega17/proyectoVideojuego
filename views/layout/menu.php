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
                <div class="text-center border-bottom">
                    <p>Sistema de biblioteca</p>
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
                    <li class="menu-item <?php echo ($archivoActual == "crear_categoria.php" ? "active" : "") ?>">
                        <a href="crear_categoria.php" class="menu-link">
                            <i class="menu-icon tf-icons bx  bx-list-ul"></i>
                            <div class="text-truncate" data-i18n="Basic">Categorias</div>
                        </a>
                    </li>
                    <li class="menu-item <?php echo ($archivoActual == "jugadores.php" ? "active" : "") ?>">
                        <a href="jugadores.php" class="menu-link">
                            <i class="menu-icon tf-icons bx  bx-user"></i>
                            <div class="text-truncate" data-i18n="Basic">Jugadores</div>
                        </a>
                    </li>

                    <li class="menu-item <?php echo ($archivoActual == "admins.php" ? "active" : "") ?>">
                        <a href="admins.php" class="menu-link">
                            <i class="fa-solid fa-user"></i>
                            <div class="text-truncate" data-i18n="Basic">Gestion administradores</div>
                        </a>
                    </li>
            
                </ul>
            </aside>
            <!-- / Menu -->