<?php
/**
 * Template Name: NFC Bio (Link Tree)
 * Minimalist landing for NFC cards.
 */
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php wp_head(); ?>
    <style>
        .nfc-body { background: #f5f5f5; text-align: center; padding: 40px 20px; font-family: sans-serif; }
        .nfc-container { max-width: 400px; margin: 0 auto; }
        .nfc-profile-img { width: 100px; height: 100px; border-radius: 50%; background: #ddd; margin: 0 auto 20px; }
        .nfc-name { font-size: 1.5rem; font-weight: bold; margin: 0; }
        .nfc-role { color: #666; margin-bottom: 30px; }
        .nfc-btn { display: block; width: 100%; padding: 15px; margin-bottom: 15px; background: #fff; border-radius: 50px; text-decoration: none; color: #333; font-weight: 600; box-shadow: 0 4px 10px rgba(0,0,0,0.05); transition: transform 0.2s; }
        .nfc-btn:active { transform: scale(0.98); }
        .nfc-btn.primary { background: #000; color: #fff; }
        .nfc-logo { font-weight: 900; letter-spacing: 2px; margin-bottom: 40px; display: block; color: #000; text-decoration: none; }
    </style>
</head>
<body class="nfc-body">

    <div class="nfc-container">
        <a href="<?php echo home_url(); ?>" class="nfc-logo">HOMAD</a>

        <div class="nfc-profile-img">
            <!-- Dynamic User Image if needed, static for now -->
        </div>

        <h1 class="nfc-name">Tu Nombre</h1>
        <p class="nfc-role">CEO & Founder | Arquitecto</p>

        <a href="<?php echo home_url('/portfolio'); ?>" class="nfc-btn primary">Ver Portafolio Rápido 📂</a>
        <a href="/contact/" class="nfc-btn">Solicitar contacto 💬</a>
        <a href="<?php echo home_url('/shop'); ?>" class="nfc-btn">Visitar Tienda Online 🛒</a>
        <a href="#" class="nfc-btn">Guardar Contacto (vCard) 📲</a>

    </div>

    <?php wp_footer(); ?>
</body>
</html>
