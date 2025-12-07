<!doctype html>

<html
    lang="en"
    class="layout-wide customizer-hide"
    data-assets-path="./assets/"
    data-template="vertical-menu-template-free">

<head>
    <meta charset="utf-8" />
    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />

    <title>Login usuarios</title>

    <meta name="description" content="" />

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="../assets/img/favicon/favicon.ico" />

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
        href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap"
        rel="stylesheet" />

    <link rel="stylesheet" href="./assets/vendor/fonts/iconify-icons.css" />

    <!-- Core CSS -->
    <!-- build:css assets/vendor/css/theme.css  -->

    <link rel="stylesheet" href="./assets/vendor/css/core.css" />
    <link rel="stylesheet" href="./assets/css/demo.css" />

    <!-- Vendors CSS -->

    <link rel="stylesheet" href="./assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css" />

    <!-- endbuild -->

    <!-- Page CSS -->
    <!-- Page -->
    <link rel="stylesheet" href="./assets/vendor/css/pages/page-auth.css" />

    <!-- Helpers -->
    <script src="./assets/vendor/js/helpers.js"></script>
    <!--! Template customizer & Theme config files MUST be included after core stylesheets and helpers.js in the <head> section -->

    <!--? Config:  Mandatory theme config file contain global vars & default theme options, Set your preferred theme option in this file.  -->

    <script src="./assets/js/config.js"></script>
</head>

<body>
    <!-- Content -->

    <div class="container-xxl">
        <div class="authentication-wrapper authentication-basic container-p-y">
            <div class="authentication-inner">
                <!-- Register -->
                <div class="card px-sm-6 px-0">
                    <div class="card-body">
                        <!-- Logo -->
                        <div class="app-brand justify-content-center">
                            <a href="index.html" class="app-brand-link gap-2">
                                <span class="app-brand-logo demo">
                                    <span class="text-primary">

                                    </span>
                                </span>
                                <span class="app-brand-text demo text-heading fw-bold">Bingo literario</span>
                            </a>
                        </div>
                        <!-- /Logo -->



                        <form id="formularioLoginAprendiz" class="mb-6">
                            <div class="mb-6">
                                <label for="nombre" class="form-label">Nombre</label>
                                <input
                                    type="text"
                                    class="form-control"
                                    id="nombre"
                                    name="nombreAprendiz"
                                    placeholder="Ingrese su nombre"
                                    autofocus />
                            </div>
                            <div class="mb-6">
                                <label for="ficha" class="form-label">Ficha</label>
                                <input
                                    type="number"
                                    class="form-control"
                                    id="ficha"
                                    name="ficha"
                                    placeholder="Ingrese ficha de formacion"
                                    autofocus />
                            </div>
                            <div class="mb-6">
                                <label for="sala" class="form-label">Codigo sala</label>
                                <input
                                    type="number"
                                    class="form-control"
                                    id="sala"
                                    name="codigoSala"
                                    placeholder="Ingrese codigo de sala"

                                    autofocus />
                            </div>
                            <div class="mb-8">
                                <div class="d-flex justify-content-between">

                                </div>
                            </div>
                            <div class="mb-6">
                                <button class="btn btn-success d-grid w-100" type="submit">Ingresar</button>
                            </div>
                        </form>


                    </div>
                </div>
                <!-- /Register -->
            </div>
        </div>
    </div>

    <!-- / Content -->



    <!-- Core JS -->

    <!-- Jquery -->
    <script src="https://code.jquery.com/jquery-3.7.1.js"></script>

    <script src="./assets/vendor/libs/popper/popper.js"></script>
    <script src="./assets/vendor/js/bootstrap.js"></script>

    <script src="./assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js"></script>

    <script src="./assets/vendor/js/menu.js"></script>

    <!-- endbuild -->

    <!-- Vendors JS -->

    <!-- Main JS -->

    <script src="./assets/js/main.js"></script>

    <!-- Page JS -->
    <script src="./assets/js/login_usuarios.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script async defer src="https://buttons.github.io/buttons.js"></script>
</body>

</html>