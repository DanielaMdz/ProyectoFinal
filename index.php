<?php
// ==========================================
// 1. CONFIGURACIÓN Y SEGURIDAD
// ==========================================
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// PARCHE DE SEGURIDAD: Definimos BASE_URL por si falla el header
if (!defined('BASE_URL')) {
    // Ajusta esto si tu carpeta se llama diferente
    define('BASE_URL', '/html/ProyectoFinal');
}

// ==========================================
// 2. INCLUIR ARCHIVOS
// ==========================================
if (!file_exists('includes/header.php')) die("<h3>Error Fatal: No encuentro includes/header.php</h3>");
include 'includes/header.php'; 

if (!file_exists('includes/db_connect.php')) die("<h3>Error Fatal: No encuentro includes/db_connect.php</h3>");
include 'includes/db_connect.php';

// ==========================================
// 3. LÓGICA DE FILTRADO Y CONSULTA
// ==========================================
$categoria_id = null;
$filtro_sql = " WHERE cantidad_en_almacen > 0 "; 

// Validar si hay categoría en la URL
if (isset($_GET['categoria']) && is_numeric($_GET['categoria'])) {
    $categoria_id = intval($_GET['categoria']);
    $filtro_sql .= " AND id_categoria = $categoria_id "; 
}

// Consulta SQL
$sql = "SELECT * FROM Productos" . $filtro_sql . " ORDER BY nombre ASC";
$resultado = mysqli_query($conn, $sql);

if (!$resultado) {
    die("<div class='alert alert-danger m-5'>Error Crítico en SQL: " . mysqli_error($conn) . "</div>");
}

$num_productos = mysqli_num_rows($resultado);
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
              <a href="#catalogo" class="btn btn-outline-dark btn-lg text-uppercase fs-6 rounded-1">
                Ver Productos
              </a>
            </div>
          </div>
        </div>

        <div class="swiper-slide py-5">
          <div class="row banner-content align-items-center">
            <div class="img-wrapper col-md-5">
               <img src="<?php echo BASE_URL; ?>/images/banner-img3.png" class="img-fluid" alt="Banner 2">
            </div>
            <div class="content-wrapper col-md-7 p-5 mb-5">
              <div class="secondary-font text-primary text-uppercase mb-4">10 - 20 % OFF</div>
              <h2 class="banner-title display-1 fw-normal">Descubre los <span class="text-primary">10 Pasos</span></h2>
              <a href="#catalogo" class="btn btn-outline-dark btn-lg text-uppercase fs-6 rounded-1">
                Comprar Ahora
              </a>
            </div>
          </div>
        </div>

        <div class="swiper-slide py-5">
          <div class="row banner-content align-items-center">
            <div class="img-wrapper col-md-5">
               <img src="<?php echo BASE_URL; ?>/images/banner-img4.png" class="img-fluid" alt="Banner 3">
            </div>
            <div class="content-wrapper col-md-7 p-5 mb-5">
              <div class="secondary-font text-primary text-uppercase mb-4">Envío Gratuito</div>
              <h2 class="banner-title display-1 fw-normal">Cuidado para <span class="text-primary">Piel Sensible</span></h2>
              <a href="#catalogo" class="btn btn-outline-dark btn-lg text-uppercase fs-6 rounded-1">
                Ver Colección
              </a>
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
            <a href="index.php?categoria=9" class="categories-item text-decoration-none">
                <iconify-icon class="category-icon" icon="fluent:drop-24-regular"></iconify-icon>
                <h5 class="mt-3 text-dark">Hidratantes</h5>
            </a>
        </div>
        <div class="col text-center">
            <a href="index.php?categoria=1" class="categories-item text-decoration-none">
                <iconify-icon class="category-icon" icon="carbon:clean"></iconify-icon>
                <h5 class="mt-3 text-dark">Limpiadores</h5>
            </a>
        </div>
        <div class="col text-center">
            <a href="index.php?categoria=3" class="categories-item text-decoration-none">
                <iconify-icon class="category-icon" icon="ri:bubble-chart-line"></iconify-icon>
                <h5 class="mt-3 text-dark">Exfoliantes</h5>
            </a>
        </div>
        <div class="col text-center">
            <a href="index.php?categoria=6" class="categories-item text-decoration-none">
                <iconify-icon class="category-icon" icon="ph:eyedropper"></iconify-icon>
                <h5 class="mt-3 text-dark">Sérums</h5>
            </a>
        </div>
        <div class="col text-center">
            <a href="index.php?categoria=10" class="categories-item text-decoration-none">
                <iconify-icon class="category-icon" icon="ph:sun"></iconify-icon>
                <h5 class="mt-3 text-dark">Protector Solar</h5>
            </a>
        </div>
    </div>
  </div>
</section>

<section id="catalogo" class="my-5">
  <div class="container pb-5">
    
    <div class="section-header d-md-flex justify-content-between align-items-center mb-5">
      <h2 class="display-3 fw-normal">
          <?php 
            if ($categoria_id) {
                echo "Filtrado por Categoría";
            } else {
                echo "Todos Nuestros Productos";
            }
          ?>
      </h2>
      <p class="secondary-font fs-5 text-muted">
          <?php echo ($num_productos > 0) ? "Mostrando $num_productos productos." : "No se encontraron productos."; ?>
      </p>
    </div>

    <div class="row g-4">
        
        <?php if ($num_productos > 0): ?>
            <?php while ($producto = mysqli_fetch_assoc($resultado)): ?>
                
                <div class="col-6 col-md-4 col-lg-3">
                    <div class="card position-relative h-100 border-0 shadow-sm">
                        
                        <a href="php/producto_detalle.php?id=<?php echo $producto['id_producto']; ?>">
                            <div style="height: 300px; overflow: hidden; border-radius: 15px; background-color: #f9f9f9;">
                                <img src="<?php echo BASE_URL . "/" . $producto['fotos']; ?>" 
                                     class="img-fluid" 
                                     style="width: 100%; height: 100%; object-fit: cover;"
                                     alt="<?php echo htmlspecialchars($producto['nombre']); ?>">
                            </div>
                        </a>
                        
                        <div class="card-body p-3">
                            <a href="php/producto_detalle.php?id=<?php echo $producto['id_producto']; ?>" class="text-decoration-none text-dark">
                                <h5 class="card-title m-0" style="font-size: 1.1rem;"><?php echo htmlspecialchars($producto['nombre']); ?></h5>
                            </a>
                            <p class="text-muted small mb-2"><?php echo htmlspecialchars($producto['fabricante']); ?></p>
                            
                            <div class="d-flex justify-content-between align-items-center mt-3">
                                <h4 class="secondary-font text-primary mb-0">$<?php echo number_format($producto['precio'], 2); ?></h4>
                                
                                <form action="php/carrito_agregar.php" method="POST">
                                    <input type="hidden" name="id_producto" value="<?php echo $producto['id_producto']; ?>">
                                    <input type="hidden" name="cantidad" value="1">
                                    <button type="submit" class="btn btn-primary rounded-circle p-0" title="Agregar al Carrito" style="width: 40px; height: 40px; display: flex; align-items: center; justify-content: center;">
                                        <iconify-icon icon="mdi:cart-plus" width="20"></iconify-icon>
                                    </button>
                                </form>
                            </div>
                        </div>

                    </div>
                </div>

            <?php endwhile; ?>
        <?php else: ?>
            <div class="col-12 py-5 text-center">
                <div class="alert alert-warning d-inline-block px-5">
                    <h3><iconify-icon icon="mdi:alert-circle-outline"></iconify-icon> No hay productos.</h3>
                    <p class="mb-0">Verifica que hayas insertado los datos en la base de datos.</p>
                </div>
            </div>
        <?php endif; ?>

    </div> </div>
</section>

<?php 
if (file_exists('includes/footer.php')) {
    include 'includes/footer.php'; 
} else {
    echo "<div class='container text-center py-5'><h3>Error: No encuentro el footer.</h3></div>";
}
?>