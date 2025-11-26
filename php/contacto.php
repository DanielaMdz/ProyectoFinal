<?php
// php/contacto.php

// 1. Lógica simple para simular el envío del correo
$mensaje_alerta = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = htmlspecialchars($_POST['nombre']);
    $mensaje_alerta = "¡Gracias $nombre! Hemos recibido tu mensaje. Te contactaremos pronto.";
}

// 2. Incluir Header (Subiendo un nivel)
require_once '../includes/header.php'; 
?>

<div class="container py-5">
    
    <div class="text-center mb-5">
        <h1 class="display-4" style="font-family: 'Chilanka', cursive; color: #8B4513;">Contáctanos</h1>
        <p class="lead text-muted">¿Dudas sobre tu rutina de Skincare? ¡Estamos aquí para ayudarte!</p>
    </div>

    <div class="row g-5">
        
        <div class="col-md-5">
            <div class="h-100 p-4 rounded-4 shadow-sm" style="background-color: #fff; border-left: 5px solid #9d6e64;">
                <h3 class="mb-4" style="color: #9d6e64;">Información Directa</h3>
                
                <div class="d-flex align-items-start mb-4">
                    <div class="me-3 text-center" style="width: 50px;">
                        <iconify-icon icon="mdi:map-marker-radius" style="font-size: 2rem; color: #8B4513;"></iconify-icon>
                    </div>
                    <div>
                        <h5 class="fw-bold mb-1">Nuestra Tienda</h5>
                        <p class="text-muted mb-0">Av. K-Beauty 123, Seúl / CDMX<br>Zona Rosa, CP 06600</p>
                    </div>
                </div>

                <div class="d-flex align-items-start mb-4">
                    <div class="me-3 text-center" style="width: 50px;">
                        <iconify-icon icon="mdi:whatsapp" style="font-size: 2rem; color: #8B4513;"></iconify-icon>
                    </div>
                    <div>
                        <h5 class="fw-bold mb-1">WhatsApp & Teléfono</h5>
                        <p class="text-muted mb-0">+52 55 1234-5678<br>Lunes a Viernes, 9am - 6pm</p>
                    </div>
                </div>

                <div class="d-flex align-items-start mb-4">
                    <div class="me-3 text-center" style="width: 50px;">
                        <iconify-icon icon="mdi:email-outline" style="font-size: 2rem; color: #8B4513;"></iconify-icon>
                    </div>
                    <div>
                        <h5 class="fw-bold mb-1">Correo Electrónico</h5>
                        <p class="text-muted mb-0">info@youngmi-store.com<br>soporte@youngmi-store.com</p>
                    </div>
                </div>

                <div class="mt-4 rounded-3 overflow-hidden">
                    <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3762.4708470727196!2d-99.16371268509332!3d19.42702058688645!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x85d1ff35f5bd1563%3A0x6c366f0e2de02ff7!2sEl%20%C3%81ngel%20de%20la%20Independencia!5e0!3m2!1ses-419!2smx!4v1677600000000!5m2!1ses-419!2smx" width="100%" height="200" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
                </div>
            </div>
        </div>

        <div class="col-md-7">
            <div class="p-5 rounded-4 shadow-sm bg-white">
                <h3 class="mb-4" style="color: #8B4513;">Envíanos un Mensaje</h3>

                <?php if ($mensaje_alerta): ?>
                    <div class="alert alert-success d-flex align-items-center">
                        <iconify-icon icon="mdi:check-circle" class="me-2 fs-4"></iconify-icon>
                        <?php echo $mensaje_alerta; ?>
                    </div>
                <?php endif; ?>

                <form action="contacto.php" method="POST">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="nombre" class="form-label fw-bold text-muted">Nombre</label>
                            <input type="text" class="form-control" id="nombre" name="nombre" placeholder="Tu nombre" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="apellido" class="form-label fw-bold text-muted">Apellido</label>
                            <input type="text" class="form-control" id="apellido" name="apellido" placeholder="Tu apellido">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label fw-bold text-muted">Correo Electrónico</label>
                        <input type="email" class="form-control" id="email" name="email" placeholder="nombre@ejemplo.com" required>
                    </div>

                    <div class="mb-3">
                        <label for="asunto" class="form-label fw-bold text-muted">Asunto</label>
                        <select class="form-select" id="asunto" name="asunto">
                            <option selected>Consulta General</option>
                            <option>Estado de mi Pedido</option>
                            <option>Asesoría de Skincare</option>
                            <option>Devoluciones</option>
                        </select>
                    </div>

                    <div class="mb-4">
                        <label for="mensaje" class="form-label fw-bold text-muted">Mensaje</label>
                        <textarea class="form-control" id="mensaje" name="mensaje" rows="5" placeholder="Cuéntanos en qué podemos ayudarte..." required></textarea>
                    </div>

                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary btn-lg" style="background-color: #9d6e64; border: none;">
                            Enviar Mensaje <iconify-icon icon="mdi:send" class="ms-2"></iconify-icon>
                        </button>
                    </div>
                </form>
            </div>
        </div>

    </div>
</div>

<?php require_once '../includes/footer.php'; ?>