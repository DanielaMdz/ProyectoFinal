<?php

ini_set('display_errors', 1);
error_reporting(E_ALL);


define('BASE_URL', 'http://localhost/html/ProyectoFinal');

$base_path = $_SERVER['DOCUMENT_ROOT'] . '/html/ProyectoFinal/';

if (file_exists($base_path . 'includes/header.php')) {
    require_once $base_path . 'includes/header.php';
} else {
    die("Error: No encuentro el archivo header.php en: " . $base_path . 'includes/');
}

if (file_exists($base_path . 'includes/db_connect.php')) {
    require_once $base_path . 'includes/db_connect.php';
} else {
    die("Error: No encuentro el archivo db_connect.php");
}

$filtro_sql = " WHERE cantidad_en_almacen > 0 "; 
$titulo_seccion = "Todos Nuestros Productos";

if (isset($_GET['categoria']) && is_numeric($_GET['categoria'])) {
    $cat_id = intval($_GET['categoria']);
    $filtro_sql .= " AND id_categoria = $cat_id ";
    $titulo_seccion = "Filtrado por Categoría";
}

$sql = "SELECT * FROM Productos" . $filtro_sql . " ORDER BY nombre ASC";
$resultado = mysqli_query($conn, $sql);
$num_productos = ($resultado) ? mysqli_num_rows($resultado) : 0;
?>

<section id="banner" style="background: #f6e8e8;">
  <div class="container">
    <div class="swiper main-swiper">
      <div class="swiper-wrapper">

        <div class="swiper-slide py-5">
          <div class="row banner-content align-items-center">
            <div class="img-wrapper col-md-5">
               <img src="<?php echo BASE_URL; ?>/images/banner-img.png" class="img-fluid" alt="Banner 1">
            </div>
            <div class="content-wrapper col-md-7 p-5 mb-5">
              <div class="secondary-font text-primary text-uppercase mb-4">Ofertas de Apertura</div>
              <h2 class="banner-title display-1 fw-normal">Tu Rutina Coreana <span class="text-primary">Ideal</span></h2>
              <a href="#catalogo" class="btn btn-outline-dark btn-lg text-uppercase fs-6 rounded-1">Ver Productos</a>
            </div>
          </div>
        </div>

        <div class="swiper-slide py-5">
          <div class="row banner-content align-items-center">
            <div class="img-wrapper col-md-5">
               <img src="<?php echo BASE_URL; ?>/images/banner-img3.png" class="img-fluid" alt="Banner 2">
            </div>
            <div class="content-wrapper col-md-7 p-5 mb-5">
              <div class="secondary-font text-primary text-uppercase mb-4">10 Pasos</div>
              <h2 class="banner-title display-1 fw-normal">Descubre el <span class="text-primary">K-Beauty</span></h2>
              <a href="#catalogo" class="btn btn-outline-dark btn-lg text-uppercase fs-6 rounded-1">Comprar Ahora</a>
            </div>
          </div>
        </div>

        <div class="swiper-slide py-5">
          <div class="row banner-content align-items-center">
            <div class="img-wrapper col-md-5">
               <img src="<?php echo BASE_URL; ?>/images/banner-img4.png" class="img-fluid" alt="Banner 3">
            </div>
            <div class="content-wrapper col-md-7 p-5 mb-5">
              <div class="secondary-font text-primary text-uppercase mb-4">Piel Sensible</div>
              <h2 class="banner-title display-1 fw-normal">Cuidado <span class="text-primary">Dermatológico</span></h2>
              <a href="#catalogo" class="btn btn-outline-dark btn-lg text-uppercase fs-6 rounded-1">Ver Más</a>
            </div>
          </div>
        </div>

      </div>
      <div class="swiper-pagination mb-5"></div>
    </div>
  </div>
</section>

<section id="categories">
  <div class="container my-3 py-5">
    <div class="row my-5">
        <div class="col text-center">
            <a href="index.php?categoria=9" class="text-decoration-none text-dark">
                <iconify-icon icon="fluent:drop-24-regular" style="font-size: 4rem; color: #744605ff;"></iconify-icon>
                <h5 class="mt-2">Hidratantes</h5>
            </a>
        </div>
        <div class="col text-center">
            <a href="index.php?categoria=1" class="text-decoration-none text-dark">
                <iconify-icon icon="carbon:clean" style="font-size: 4rem; color: #744605ff;"></iconify-icon>
                <h5 class="mt-2">Limpiadores</h5>
            </a>
        </div>
        <div class="col text-center">
            <a href="index.php?categoria=6" class="text-decoration-none text-dark">
                <iconify-icon icon="ph:eyedropper" style="font-size: 4rem; color: #744605ff;"></iconify-icon>
                <h5 class="mt-2">Sérums</h5>
            </a>
        </div>
        <div class="col text-center">
            <a href="index.php?categoria=10" class="text-decoration-none text-dark">
                <iconify-icon icon="ph:sun" style="font-size: 4rem; color: #744605ff;"></iconify-icon>
                <h5 class="mt-2">Protector Solar</h5>
            </a>
        </div>
    </div>
  </div>
</section>

<section id="catalogo" class="my-5">
  <div class="container pb-5">
    
    <div class="section-header d-flex justify-content-between align-items-center mb-5">
      <h2 class="display-3 fw-normal"><?php echo $titulo_seccion; ?></h2>
      <p class="fs-5 text-muted">Mostrando <?php echo $num_productos; ?> productos.</p>
    </div>

    <div class="row g-4">
        <?php if ($num_productos > 0): ?>
            <?php while ($producto = mysqli_fetch_assoc($resultado)): ?>
                <div class="col-6 col-md-4 col-lg-3">
                    <div class="card h-100 border-0 shadow-sm">
                        <a href="php/producto_detalle.php?id=<?php echo $producto['id_producto']; ?>">
                            <div style="height: 250px; overflow: hidden; border-radius: 15px; background: #fff; text-align: center;">
                                <img src="<?php echo BASE_URL . '/' . $producto['fotos']; ?>" 
                                     class="img-fluid" 
                                     style="height: 100%; object-fit: contain;" 
                                     alt="<?php echo htmlspecialchars($producto['nombre']); ?>">
                            </div>
                        </a>
                        
                        <div class="card-body p-3">
                            <h6 class="card-title m-0"><?php echo htmlspecialchars($producto['nombre']); ?></h6>
                            <p class="text-muted small mb-2"><?php echo htmlspecialchars($producto['fabricante']); ?></p>
                            
                            <div class="d-flex justify-content-between align-items-center mt-3">
                                <h5 class="text-primary mb-0">$<?php echo number_format($producto['precio'], 2); ?></h5>
                                
                                <form action="php/carrito_agregar.php" method="POST">
                                    <input type="hidden" name="id_producto" value="<?php echo $producto['id_producto']; ?>">
                                    <input type="hidden" name="cantidad" value="1">
                                    <button type="submit" class="btn btn-primary rounded-circle p-0" style="width: 35px; height: 35px; display: flex; align-items: center; justify-content: center;">
                                        <iconify-icon icon="mdi:cart-plus" width="18"></iconify-icon>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <div class="col-12 text-center">
                <div class="alert alert-warning">
                    <h3>No hay productos en esta categoría.</h3>
                </div>
            </div>
        <?php endif; ?>
    </div>

  </div>
</section>

<?php 
// Footer usando ruta física para asegurar que cargue
if (file_exists($base_path . 'includes/footer.php')) {
    require_once $base_path . 'includes/footer.php';
}
?>