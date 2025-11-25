<?php 
session_start(); // Inicia la sesión para el login/carrito
?>
<!DOCTYPE html>
<html lang="es">

<head>
  <title>Young - Mi</title>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="description" content="Proyecto Final de Tienda Online">

  <base href="http://localhost/html/ProyectoFinal/">

  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@9/swiper-bundle.min.css" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
  
  <link rel="stylesheet" type="text/css" href="css/vendor.css">
  <link rel="stylesheet" type="text/css" href="css/style.css"> 

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Chilanka&family=Montserrat:wght@300;400;500&display=swap"
    rel="stylesheet">
</head>

<body>

  <svg xmlns="http://www.w3.org/2000/svg" style="display: none;">
      <symbol xmlns="http://www.w3.org/2000/svg" id="user" viewBox="0 0 24 24"><path fill="currentColor" d="M15.71 12.71a6 6 0 1 0-7.42 0a10 10 0 0 0-6.22 8.18a1 1 0 0 0 2 .22a8 8 0 0 1 15.9 0a1 1 0 0 0 1 .89h.11a1 1 0 0 0 .88-1.1a10 10 0 0 0-6.25-8.19ZM12 12a4 4 0 1 1 4-4a4 4 0 0 1-4 4Z" /></symbol>
      <symbol xmlns="http://www.w3.org/2000/svg" id="cart" viewBox="0 0 24 24"><path fill="currentColor" d="M8.5 19a1.5 1.5 0 1 0 1.5 1.5A1.5 1.5 0 0 0 8.5 19ZM19 16H7a1 1 0 0 1 0-2h8.491a3.013 3.013 0 0 0 2.885-2.176l1.585-5.55A1 1 0 0 0 19 5H6.74a3.007 3.007 0 0 0-2.82-2H3a1 1 0 0 0 0 2h.921a1.005 1.005 0 0 1 .962.725l.155.545v.005l1.641 5.742A3 3 0 0 0 7 18h12a1 1 0 0 0 0-2Zm-1.326-9l-1.22 4.274a1.005 1.005 0 0 1-.963.726H8.754l-.255-.892L7.326 7ZM16.5 19a1.5 1.5 0 1 0 1.5 1.5a1.5 1.5 0 0 0-1.5-1.5Z" /></symbol>
      </svg>
  
  <div class="offcanvas offcanvas-end" data-bs-scroll="true" tabindex="-1" id="offcanvasSearch" aria-labelledby="Search">
    </div>

  <header>
    <div class="container py-2">
      <div class="row py-4 pb-0 pb-sm-4 align-items-center ">

        <div class="col-sm-4 col-lg-3 text-center text-sm-start">
          <div class="main-logo">
            <a href="index.php">
              <img src="images/logo.webp" alt="logo" class="img-fluid">
            </a>
          </div>
        </div>

        <div class="col-sm-6 offset-sm-2 offset-md-0 col-lg-5 d-none d-lg-block">
            <div class="search-bar border rounded-2 px-3 border-dark-subtle">
                <form id="search-form" class="text-center d-flex align-items-center" action="php/busqueda.php" method="GET">
                    <input type="text" name="query" class="form-control border-0 bg-transparent" placeholder="Buscar Skincare..." />
                    <button type="submit" style="border:none; background:none;">
                        <iconify-icon icon="tabler:search" class="fs-4 me-3"></iconify-icon>
                    </button>
                </form>
            </div>
        </div>

        <div class="col-sm-8 col-lg-4 d-flex justify-content-end gap-5 ...">
            </div>
      </div>
    </div>

    <div class="container-fluid"><hr class="m-0"></div>

    <div class="container">
      <nav class="main-menu d-flex navbar navbar-expand-lg ">

        <div class="d-flex d-lg-none align-items-end mt-3">
          <ul class="d-flex justify-content-end list-unstyled m-0">
            
            <?php if (isset($_SESSION['id_usuario'])): // --- LOGUEADO (MÓVIL) --- ?>
              <li><a href="php/perfil.php" class="mx-3" title="Mi Perfil"><iconify-icon icon="healthicons:person" class="fs-4"></iconify-icon></a></li>
            <?php else: // --- DESCONECTADO (MÓVIL) --- ?>
              <li><a href="php/login.php" class="mx-3" title="Iniciar Sesión"><iconify-icon icon="healthicons:person" class="fs-4"></iconify-icon></a></li>
            <?php endif; ?>
            
            <li><a href="php/carrito.php" class="mx-3" title="Carrito de Compras">
                <iconify-icon icon="mdi:cart" class="fs-4 position-relative"></iconify-icon>
                <span class="position-absolute translate-middle badge rounded-circle bg-primary pt-2">0</span>
            </a></li>
            </ul>
        </div>

        <button class="navbar-toggler" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasNavbar" aria-controls="offcanvasNavbar">
          <span class="navbar-toggler-icon"></span>
        </button>

        <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasNavbar" aria-labelledby="offcanvasNavbarLabel">
          <div class="offcanvas-header justify-content-center">
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
          </div>
          <div class="offcanvas-body justify-content-between">
            
            <select class="filter-categories border-0 mb-0 me-5" onchange="if (this.value) window.location.href=this.value;">
              <option value="index.php">Comprar por Categoría</option>
              <option value="index.php?categoria=1">Limpiadores</option>
              <option value="index.php?categoria=4">Tónicos</option>
              <option value="index.php?categoria=6">Sérums</option>
              <option value="index.php?categoria=9">Hidratantes</option>
              <option value="index.php?categoria=10">Protector Solar</option>
              <option value="index.php?categoria=7">Mascarillas</option>
            </select>

            <ul class="navbar-nav menu-list list-unstyled d-flex gap-md-3 mb-0">
              <li class="nav-item">
                <a href="index.php" class="nav-link active">Catálogo</a>
              </li>
              <li class="nav-item">
                <a href="php/contacto.php" class="nav-link">Contacto</a>
              </li>
              
              <?php if (isset($_SESSION['id_usuario'])): // --- LOGUEADO (MENÚ) --- ?>
                <li class="nav-item"><a href="php/historial.php" class="nav-link">Mis Compras</a></li>
                <?php endif; ?>
            </ul>

            <div class="d-none d-lg-flex align-items-end">
              <ul class="d-flex justify-content-end list-unstyled m-0">
                
                <?php if (isset($_SESSION['id_usuario'])): // --- LOGUEADO (ESCRITORIO) --- ?>
                  
                  <li><a href="php/perfil.php" class="mx-3" title="Mi Perfil">
                      <iconify-icon icon="healthicons:person" class="fs-4"></iconify-icon>
                  </a></li>
                  <li><a href="php/logout.php" class="mx-3" title="Cerrar Sesión">Cerrar Sesión</a></li>
                
                <?php else: // --- DESCONECTADO (ESCRITORIO) --- ?>
                  
                <li class="nav-item dropdown">
    
                    <a class="nav-link dropdown-toggle mx-3" href="#" id="navbarUserDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false" title="Mi Cuenta">
                    <iconify-icon icon="healthicons:person" class="fs-4"></iconify-icon>
                    </a>
                    
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarUserDropdown">
                    <li><a class="dropdown-item" href="php/login.php">Iniciar Sesión</a></li>
                    <li><a class="dropdown-item" href="php/registro.php">Crear Cuenta</a></li>
                    </ul>
                </li>
                
                <?php endif; ?>
                
                <li>
                  <a href="php/carrito.php" class="mx-3" title="Carrito de Compras">
                    <iconify-icon icon="mdi:cart" class="fs-4 position-relative"></iconify-icon>
                    <span class="position-absolute translate-middle badge rounded-circle bg-primary pt-2">0</span>
                  </a>
                </li>
              </ul>
            </div>

          </div>
        </div>
      </nav>
    </div>
  </header> 