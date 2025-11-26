</main> <style>
    /* Fondo Blanco para todo el footer */
    #footer, #footer-bottom {
        background-color: #ffffff !important;
        color: #5A2D0A !important; /* Café oscuro para el texto */
        border-top: 1px solid #FADDE1; /* Una línea sutil rosa arriba */
    }

    /* Títulos del footer */
    #footer h3 {
        color: #8B4513 !important; /* Café más claro para títulos */
        font-family: 'Chilanka', cursive;
        font-weight: bold;
    }

    /* Enlaces (Links) */
    #footer .nav-link, 
    #footer-bottom a {
        color: #5A2D0A !important;
        transition: 0.3s;
    }

    /* Hover en enlaces (Rosa al pasar el mouse) */
    #footer .nav-link:hover, 
    #footer-bottom a:hover {
        color: #F8C3CD !important;
        padding-left: 5px; /* Pequeño efecto de movimiento */
    }

    /* Íconos Sociales */
    .social-icon {
        color: #8B4513 !important;
        border: 1px solid #FADDE1 !important;
    }
    
    .social-icon:hover {
        background-color: #F8C3CD !important;
        color: white !important;
        border-color: #F8C3CD !important;
    }
    
    /* Input del Newsletter */
    .search-bar input {
        color: #333 !important; /* Texto oscuro al escribir */
    }
    .search-bar {
        border: 1px solid #ccc !important;
    }
</style>

<footer id="footer" class="my-5">
    <div class="container py-5 my-5">
      <div class="row">

        <div class="col-md-3">
          <div class="footer-menu">
            <img src="<?php echo BASE_URL; ?>/images/logo.webp" alt="logo" class="img-fluid" style="max-height: 60px;">
            <p class="blog-paragraph fs-6 mt-3">Suscríbete a nuestro boletín para recibir las mejores ofertas de K-Beauty.</p>
            <div class="social-links">
              <ul class="d-flex list-unstyled gap-2">
                <li class="social"><a href="#"><iconify-icon class="social-icon" icon="ri:facebook-fill"></iconify-icon></a></li>
                <li class="social"><a href="#"><iconify-icon class="social-icon" icon="ri:twitter-fill"></iconify-icon></a></li>
                <li class="social"><a href="#"><iconify-icon class="social-icon" icon="ri:instagram-fill"></iconify-icon></a></li>
                <li class="social"><a href="#"><iconify-icon class="social-icon" icon="ri:youtube-fill"></iconify-icon></a></li>
              </ul>
            </div>
          </div>
        </div>

        <div class="col-md-3">
          <div class="footer-menu">
            <h3>Enlaces Rápidos</h3>
            <ul class="menu-list list-unstyled">
              <li class="menu-item"><a href="<?php echo BASE_URL; ?>/index.php" class="nav-link">Inicio</a></li>
              <li class="menu-item"><a href="<?php echo BASE_URL; ?>/index.php" class="nav-link">Catálogo</a></li>
              <li class="menu-item"><a href="<?php echo BASE_URL; ?>/php/contacto.php" class="nav-link">Contacto</a></li>
              <li class="menu-item"><a href="<?php echo BASE_URL; ?>/php/registro.php" class="nav-link">Registrarse</a></li>
            </ul>
          </div>
        </div>

        <div class="col-md-3">
          <div class="footer-menu">
            <h3>Ayuda</h3>
              <ul class="menu-list list-unstyled">
                <li class="menu-item"><a href="#" class="nav-link">Preguntas Frecuentes</a></li>
                <li class="menu-item"><a href="#" class="nav-link">Envíos y Devoluciones</a></li>
                <li class="menu-item"><a href="#" class="nav-link">Métodos de Pago</a></li>
                <li class="menu-item"><a href="#" class="nav-link">Términos y Condiciones</a></li>
              </ul>
          </div>
        </div>

        <div class="col-md-3">
          <div>
            <h3>Newsletter</h3>
            <p class="blog-paragraph fs-6">Recibe descuentos exclusivos en tu correo.</p>
            <div class="search-bar border rounded-pill border-dark-subtle px-2">
              <form class="text-center d-flex align-items-center" action="" method="">
                <input type="text" class="form-control border-0 bg-transparent" placeholder="Tu correo electrónico" />
                <iconify-icon class="send-icon" icon="tabler:location-filled" style="color: #9d6e64 !important;"></iconify-icon>
              </form>
            </div>
          </div>
        </div>

      </div>
    </div>
</footer>

<div id="footer-bottom">
    <div class="container">
      <hr class="m-0">
      <div class="row mt-3">
        <div class="col-md-6 copyright">
          <p class="secondary-font">© 2025 Young-Mi K-Beauty. Todos los derechos reservados.</p>
        </div>
        <div class="col-md-6 text-md-end">
          <p class="secondary-font">Proyecto Final</p>
        </div>
      </div>
    </div>
</div>

<script src="<?php echo BASE_URL; ?>/js/jquery-1.11.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/swiper@9/swiper-bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>

<script src="<?php echo BASE_URL; ?>/js/plugins.js"></script>
<script src="<?php echo BASE_URL; ?>/js/script.js"></script>

</body> 
</html>