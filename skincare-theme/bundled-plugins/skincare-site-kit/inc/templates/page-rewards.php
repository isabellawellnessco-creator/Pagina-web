[sk_marquee]
<div class="sk-rewards-page-wrapper" style="max-width: 1200px; margin: 0 auto; padding: 20px;">

    <!-- Hero Section -->
    <div class="sk-hero-section" style="text-align: center; padding: 60px 0; background-color: #F8F5F1; border-radius: 12px; margin-bottom: 40px;">
        <h1 style="color: #0F3062; font-size: 3rem; margin-bottom: 10px;">Recompensas Skin Cupid</h1>
        <p style="color: #E5757E; font-size: 1.2rem; font-weight: bold;">Gana puntos y canjea descuentos exclusivos</p>
        <?php if ( ! is_user_logged_in() ) : ?>
            <a href="/login/" class="button" style="background-color: #0F3062; color: #fff; padding: 10px 25px; border-radius: 25px; text-decoration: none; margin-top: 20px; display: inline-block;">Únete Ahora</a>
            <p style="font-size: 0.9rem; margin-top: 10px;">¿Ya tienes cuenta? <a href="/login/">Inicia sesión</a></p>
        <?php else : ?>
            <p style="font-size: 1rem; margin-top: 20px;">Bienvenido de nuevo. Tienes <strong style="color: #E5757E;">[sk_user_points]</strong> puntos.</p>
        <?php endif; ?>
    </div>

    <!-- How it works -->
    <div class="sk-how-it-works" style="text-align: center; margin-bottom: 60px;">
        <h2 style="color: #0F3062; margin-bottom: 30px;">Cómo Funciona</h2>
        <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 30px;">
            <div class="sk-step">
                <div style="font-size: 40px; color: #E5757E; margin-bottom: 15px;"><i class="fas fa-user-plus"></i></div>
                <h3 style="color: #0F3062;">1. Únete</h3>
                <p>Crea una cuenta y obtén puntos instantáneamente.</p>
            </div>
            <div class="sk-step">
                <div style="font-size: 40px; color: #E5757E; margin-bottom: 15px;"><i class="fas fa-shopping-bag"></i></div>
                <h3 style="color: #0F3062;">2. Gana</h3>
                <p>Gana 5 puntos por cada £1 que gastes.</p>
            </div>
            <div class="sk-step">
                <div style="font-size: 40px; color: #E5757E; margin-bottom: 15px;"><i class="fas fa-gift"></i></div>
                <h3 style="color: #0F3062;">3. Canjea</h3>
                <p>Usa tus puntos para obtener descuentos en tus productos favoritos.</p>
            </div>
        </div>
    </div>

    <!-- Ways to Earn -->
    <div class="sk-ways-to-earn" style="margin-bottom: 60px;">
        <h2 style="text-align: center; color: #0F3062; margin-bottom: 30px;">Formas de Ganar</h2>
        [sk_rewards_actions]
    </div>

    <!-- Rewards Catalog -->
    <div class="sk-rewards-catalog-section" style="margin-bottom: 60px;">
        <h2 style="text-align: center; color: #0F3062; margin-bottom: 30px;">Catálogo de Recompensas</h2>
        [sk_rewards_catalog]
    </div>

    <!-- VIP Tiers -->
    <div class="sk-vip-tiers" style="margin-bottom: 60px;">
        <h2 style="text-align: center; color: #0F3062; margin-bottom: 30px;">Niveles VIP</h2>
        [sk_rewards_castle]
    </div>

    <!-- FAQ -->
    <div class="sk-faq-section">
        <h2 style="text-align: center; color: #0F3062; margin-bottom: 30px;">Preguntas Frecuentes</h2>
        [sk_faq_accordion]
    </div>

</div>
