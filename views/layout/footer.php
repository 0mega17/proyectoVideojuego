 <?php

    // Obtener el nombre del archivo actual
    $archivoActual = basename($_SERVER["PHP_SELF"]);
    ?>
 </div>
 <!-- / Content -->

 <!-- Footer -->
 <!-- Basic footer -->
 <section id="basic-footer">
     <footer class="footer bg-light">
         <div
             class="container-fluid d-flex flex-md-row flex-column justify-content-between align-items-md-center gap-1 container-p-x py-4">
             <div>
                 <a
                     href="https://demos.themeselection.com/sneat-bootstrap-html-admin-template/html/vertical-menu-template/"
                     target="_blank"
                     class="footer-brand fw-bold">Sneat</a>
                 ©
             </div>
             <div>
                 <a href="https://themeselection.com/license/" class="footer-link me-6" target="_blank">License</a>
                 <a href="javascript:void(0)" class="footer-link me-6">Help</a>
                 <a href="javascript:void(0)" class="footer-link me-6">Contact</a>
                 <a href="javascript:void(0)" class="footer-link">Terms &amp; Conditions</a>
             </div>
         </div>
     </footer>
 </section>
 <!-- / Footer -->

 <div class="content-backdrop fade"></div>
 </div>
 <!-- Content wrapper -->
 </div>
 <!-- / Layout page -->
 </div>

 <!-- Overlay -->
 <div class="layout-overlay layout-menu-toggle"></div>
 </div>
 <!-- / Layout wrapper -->

 <!-- Core JS -->
 <!-- Jquery -->
 <script src="https://code.jquery.com/jquery-3.7.1.js"></script>

 <!-- Sweet alert -->
 <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

 <script src="./assets/vendor/libs/popper/popper.js"></script>
 <script src="./assets/vendor/js/bootstrap.js"></script>

 <script src="./assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js"></script>

 <script src="./assets/vendor/js/menu.js"></script>
 <!-- endbuild -->

 <!-- Main JS -->
 <script src="./assets/js/main.js"></script>


 <!-- Place this tag before closing body tag for github widget button. -->
 <script async defer src="https://buttons.github.io/buttons.js"></script>


 <!-- BOOSTRAP 5 DATATABLES -->
 <script src="./assets/js/datatables.js"></script>
 <script src="https://cdn.datatables.net/2.3.4/js/dataTables.js"></script>
 <script src="https://cdn.datatables.net/2.3.4/js/dataTables.bootstrap5.js"></script>
 <script src="https://cdn.datatables.net/responsive/3.0.7/js/dataTables.responsive.js"></script>


 <!-- FontAwesome -->
 <script src="https://kit.fontawesome.com/4c0cbe7815.js" crossorigin="anonymous"></script>

 <?php if ($archivoActual == "composiciones.php"): ?>
     <script src="./assets/js/composiciones.js"></script>
     <script src="./assets/js/log_out.js"></script>
     <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
     <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" />
 <?php endif ?>

 <?php if ($archivoActual == "balotas.php"): ?>
     <script src="./assets/js/balotas.js"></script>
     <script src="./assets/js/mostrar_codigo_sala.js"></script>
 <?php endif ?>
 <?php if ($archivoActual == "sala.php"): ?>
     <script src="./assets/js/categorias.js"></script>
     <script src="./assets/js/crear_sala.js"></script>
 <?php endif ?>


 <?php if ($archivoActual == "crear_categoria.php"): ?>

     <script src="./assets/js/crear_categorias.js"></script>
 <?php endif ?>
 <?php if ($archivoActual == "jugadores.php"): ?>
     <script src="./assets/js/jugadores.js"></script>
 <?php endif ?>
 </body>

 </html>